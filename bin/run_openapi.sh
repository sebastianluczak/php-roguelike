#!/usr/bin/env bash
docker-compose exec "app php -S 0.0.0.0:8000 -t public &"