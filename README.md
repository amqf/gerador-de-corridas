# About the project

Features:
- Create a race
- Cancel a race
- Pay a race    
- View a race

The race cost: 5 cents by meter

Para a arquitetura do projeto foram utilizados alguns conceitos de Clean Architecture, isolamento da camada de persistência e camada de apresentação, bem como a centralização das regras de negócio no domínio com design conceituado no DDD como agregado, entidade, por exemplo.

## About Create a Race

Exemplo de curl executar esta ação:

```bash
curl -sS -X POST \
    -H 'Content-Type: application/json' \
    -d '{ "origin": { "latitude": -22.302407981128297, "longitude": -49.10229971613744 }, "destiny": { "latitude": -22.302715314470994, "longitude": -49.101353497779776 } }' \
    http://localhost/races
```

Quando é informada uma latitude menor que -90 ou maior que 90 verá o erro:

```json
{
    "error": "Invalid latitude: $latitude. It must be between -90 and 90"
}
```

Quando é informada uma longitude menor que -180 ou maior que 180 verá o erro:

```json
{
    "error": "Invalid longitude: $longitude. It must be between -180 and 180"
}
```

## About Cancel a Race

Exemplo de curl para executar esta ação:

```bash
curl -sS -X POST \
    -H 'Content-Type: application/json' \
    -d '{ "description": "Não quero mais", "reason": "Others" }' \
    http://localhost/races/{race_uuid}/cancellation
```

Quando se tenta cancelar uma corrida já cancelada ou se está tentando cancelar uma corrida por um motivo qualquer depois 3 minutos verá o erro:

```json
{
    "error": "Cannot cancel the race now"
}
```

É possível cancelar uma corrida por dois motivos diferentes:

- "Others" para quando "The race was canceled for other reasons."
- "ForceMajeure" para quando "The race was canceled due to unforeseen and unavoidable circumstances."

## About Pay a Race

Exemplo de curl para executar esta ação:

```bash
curl -sS -X POST \
    -H 'Content-Type: application/json' \
    -d '{ "amount": 1.0 }' \
    http://localhost/races/{race_id}/payment
```

Quando se tenta pagar uma corrida já cancelada verá o erro:

```json
{
    "error": "Cannot pay a cancelled race"
}
```

Quando se tenta pagar a corrida com um valor menor que o custo dela verá o erro:

```json
{
    "error": "You cannot pay with R$ %d. The race cost is R$ %d"
}
```

## About View a Race

```bash
curl -sS -X GET \
    -H 'Content-Type: application/json' \
    http://localhost/races/{race_uuid}
```

```json
{
    "error": ""
}
```

# How run it?

## With Docker

```
$ docker network create global_network
$ docker compose up --build -d
$ docker exec app-races composer install
```

## With Kool.Dev

Read more about in https://kool.dev/

```
$ curl -fsSL https://kool.dev/install | bash
$ kool start
$ kool run composer install
# TODO: complete with more info here
```

## Without Docker in Linux or MacOS

**!!! ATTENTION !!!: Perform it your own risk.**

You can install all dependencies using `./install_dependencies.sh` script.
It can identify if you're using MacOS or Linux and run the appropriated command.


### Manually

You can mannually all these dependencies:

```
- install php8.3
- install composer
- install curl
- install php8.3-mbstring
- install php8.3-sqlite3
- install jq
```

# Abount Dependencies

This dependencies is needed each one for a particular case.

```
- php8.3 (required for run project)
- php8.3-sqlite3 (required for run project / for presistence purpouse)
- php8.3-mbstring (required for run project tests / for "pest test suite" purpouse)
- jq (if you want use auto_cancel_after command in request.sh script)
```