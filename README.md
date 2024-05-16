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

# Structure

```
src
├── App
│   ├── Domain
│   │   ├── Entities        # Contém as entidades do domínio da aplicação
│   │   ├── Repositories    # Responsável por definir as interfaces de repositório do domínio
│   │   ├── UseCases        # Armazena os casos de uso que representam as operações de alto nível do domínio
│   │   └── Services        # Contém serviços de domínio que encapsulam lógica de negócio complexa
│   └── Infra
│       ├── Adapters        # Adaptadores que conectam o domínio com tecnologias externas ou infraestrutura
│       └── Persistence     # Componentes relacionados à persistência de dados da aplicação
│           └── SQLite      # Componentes específicos para persistência usando SQLite
└── ...
```