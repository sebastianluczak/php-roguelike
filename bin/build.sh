#!/usr/bin/env bash
docker-compose down &&
docker-compose up -d --build &&
docker-compose exec app php /app/bin/console game:start
