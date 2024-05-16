# SETUP

```
docker network create global_network
docker exec app-races composer install
```

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


# dependencies

php8.3
php8.3-sqlite3 (presistence)
php8.3-mbstring (pest)