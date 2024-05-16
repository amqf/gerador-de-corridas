#!/usr/bin/env bash

docker-compose up --build
docker-compose exec app composer install