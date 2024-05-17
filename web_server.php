<?php


use App\Infra\Persistence\SQLite\RaceSQLiteRepository;
use App\Presentation\Web\Controllers\CancelRaceController;
use App\Presentation\Web\Controllers\CreateRaceController;
use App\Presentation\Web\Controllers\HealthController;
use App\Presentation\Web\Controllers\ViewRaceController;
use App\Presentation\Web\Controllers\PaymentController;
use App\Domain\Repositories\RaceRepository;
use Laminas\Di\Config;
use Laminas\Di\Injector;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteRunner;
use App\Domain\UseCases\CreateRace;
use Laminas\Diactoros\ServerRequest;

/** @var Injector */
$diInjector = new Injector(
    new Config([
        'preferences' => [
            RaceRepository::class => RaceSQLiteRepository::class,
        ],
        // 'factories' => [
        //     RaceRepository::class => fn($di) => new RaceSQLiteRepository(DATABASE_PATH, 'races'),
        //     CreateRace::class => fn($di) => new CreateRace($di->get(RaceRepository::class)),
        // ],
    ])
);

function handle_request_with(string $controller, Injector $diInjector): callable
{
    return function (Request $request, Response $response, array $args = []) use ($controller, $diInjector) {
        return $diInjector->create($controller)->handle($request, $response, $args);
    };
}

$app = AppFactory::create();

// Define Custom Error Handler
$customErrorHandler = function (
    ServerRequest $request,
    Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails,
    ?LoggerInterface $logger = null
) use ($app) {    
    
    #TODO: in this case, use $logger too
    $payload = [
        'error' => 'Oops! Não estávamos esperando por este erro, não é mesmo?',
    ];

    if($exception instanceof DomainException)
    {
        $payload = [
            'error' => $exception->getMessage(),
        ];
    }

    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(
        json_encode($payload, JSON_UNESCAPED_UNICODE)
    );

    return $response;
};

// Add Error Middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

$app->add(function (Request $request, $routeRunner) {
    // Decode request body json content
    // TODO: Isolate it in another middlware
    $rawBody = $request->getBody()->getContents();
    $parsedBody = json_decode($rawBody, true);
    $request = $request->withParsedBody($parsedBody);

    // Add Content-Type application/json for do browser parse JSON content response
    // TODO: Isolate it in another middlware
    $response = $routeRunner->handle($request);
    $response = $response->withHeader('Content-Type', 'application/json');

	return $response;
});

$app->get('/', handle_request_with(HealthController::class, $diInjector));
$app->post('/races', handle_request_with(CreateRaceController::class, $diInjector));
$app->post('/races/{id}/cancellation', handle_request_with(CancelRaceController::class, $diInjector));
$app->get('/races/{id}', handle_request_with(ViewRaceController::class, $diInjector));
$app->post('/races/{id}/payment', handle_request_with(PaymentController::class, $diInjector));

$app->run();