<?php

namespace App\Domain\Entities;

use App\Domain\Entities\ValueObjects\Id;
use App\Domain\Entities\ValueObjects\GeoCoordinate;
use App\Domain\Entities\ValueObjects\Payment;
use App\Domain\Entities\ValueObjects\RaceCancellation;
use App\Domain\Entities\ValueObjects\Enums\RaceCancellationReason;
use DateTimeImmutable;
use DateTimeZone;
use DomainException;

final class AggregatedRace
{
    private float $cost;

    private function __construct(
        private Id $id,
        private GeoCoordinate $origin,
        private GeoCoordinate $destiny,
        private DateTimeImmutable $requestedAt,
        private ?RaceCancellation $cancellation,
        private ?Payment $payment,
        private bool $isPersisted,
    )
    {
        $this->cost = $this->calcCost($origin, $destiny);
    }

    /**
     * @param array $data [
     *  'amount' => float
     * ]
     */
    public function pay(array $data) : void
    {
        if($this->isCancelled())
        {
            throw new DomainException('Cannot pay a cancelled race');
        }

        $data['timestamp'] = new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo'));

        /** @var Payment $payment */
        $payment = Payment::create($data);

        if($payment->getAmount() < $this->getCost())
        {
            throw new DomainException(
                sprintf(
                    "You cannot pay with R$ %d. The race cost is R$ %d", 
                    $payment->getAmount(),
                    $this->getCost()
                )
            );
        }

        $this->payment = $payment;
    }

    /**
     * Apply the race cost calculating distance between origin and destiny
     * This actually sum 0.5 cents (in real) for each meter
     */
    private function calcCost(GeoCoordinate $origin, GeoCoordinate $destiny) : float
    {
        /** @var float */
        $meters = $origin->diffInMeters($destiny);
        return $meters * 0.05;
    }

    /**
     * It's used for really create a new the race for persiste it.
     * 
     * @param array $data [
     *  'origin' => [
     *      'latitude' => string,
     *      'longitude' => string,
     *  ],
     *  'destiny' => [
     *      'latitude' => string,
     *      'longitude' => string,
     *  ]
     * ]
     */
    public static function create(array $data) : self
    {
        /** @var DateTimeImmutable $currentDateTime */
        $currentDateTime = new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo'));


        // var_dump($data['origin'], $data['destiny']);die();

        /** @var AggregatedRace */
        $aggregatedRace = new self(
            Id::generate(),
            GeoCoordinate::create($data['origin']),
            GeoCoordinate::create($data['destiny']),
            $currentDateTime,
            cancellation: null,
            payment: null,
            isPersisted: false
        );

        return $aggregatedRace;
    }

    /**
     * Make a aggregated race with data comming from database for example.
     * It's for case when the race was created before.
     * 
     * This method require the race id,
     * but cancellation and cancellation data are opationals.
     * 
     * @param array $data [
     *  'id' => uuid,
     *  'requested_at' => string,
     *  'origin' => [
     *      'latitude' => string,
     *      'longitude' => string,
     *  ],
     *  'destiny' => [
     *      'latitude' => string,
     *      'longitude' => string,
     *  ]
     * ]
     */
    public static function make(array $data) : self
    {
        /** @var DateTimeImmutable $requestedAt */
        $requestedAt = new DateTimeImmutable($data['requested_at'], new DateTimeZone('America/Sao_Paulo'));

        /** @var ?RaceCancellation */
        $cancellation = isset($data['cancellation']) ?
            RaceCancellation::create($data['cancellation'])
            : null;

        $payment = isset($data['payment']) ?
            Payment::make($data['payment'])
            : null;

        /** @var AggregatedRace */
        $aggregatedRace = new self(
            Id::create($data['id']),
            GeoCoordinate::create($data['origin']),
            GeoCoordinate::create($data['destiny']),
            $requestedAt,
            $cancellation,
            $payment,
            isPersisted: true
        );

        return $aggregatedRace;
    }

    public function notPersisted() : bool
    {
        return !$this->isPersisted;
    }

    public function getId() : Id|null
    {
        return $this->id;
    }

    public function getOrigin() : GeoCoordinate
    {
        return $this->origin;
    }

    public function getDestiny() : GeoCoordinate
    {
        return $this->destiny;
    }

    public function getPayment() : ?Payment
    {
        return $this->payment;
    }

    public function getRequestedAt() : DateTimeImmutable
    {
        return $this->requestedAt;
    }

    public function getCanceledAt() : DateTimeImmutable|null
    {
        return $this->canceledAt;
    }

    public function isPaid() : bool
    {
        return $this->payment instanceof Payment;
    }

    public function wasRequestedAfter3MinutesAgo() : bool
    {
        /** @var DateTimeImmutable $currentDateTime */
        $currentDateTime = new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo'));
        $diffInSeconds = $currentDateTime->getTimestamp() - $this->getRequestedAt()->getTimestamp();
        return $diffInSeconds > 180;
    }

    public function cancel(array $data) : void
    {
        /** @var RaceCancellation $cancellation */
        $cancellation = RaceCancellation::create($data);

        if($this->isCancelled() || ($this->wasRequestedAfter3MinutesAgo() && $cancellation->matchReason(RaceCancellationReason::Others))
        )
        {
            throw new DomainException('Cannot cancel the race now');
        }

        $this->cancellation = $cancellation;
    }

    public function isCancelled() : bool
    {
        return $this->cancellation instanceof RaceCancellation;
    }

    public function getCancellation() : ?RaceCancellation
    {
        return $this->cancellation;
    }

    public function getCost() : float
    {
        return $this->cost;
    }

    public function toArray() : array
    {
        $data = [
            'id' => $this->getId()->__toString(),
            'cost' => $this->getCost(),
            'origin' => $this->getOrigin()->toArray(),
            'destiny' => $this->getDestiny()->toArray(),
            'payment' => $this->getPayment()?->toArray(),
            'requested_at' => $this->getRequestedAt()->format('Y-m-d H:i:s'),
        ];

        if($this->isCancelled())
        {
            $data['cancellation'] = $this->getCancellation()->toArray();
        }

        return $data;
    }
}