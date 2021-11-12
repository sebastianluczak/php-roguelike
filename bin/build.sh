#!/usr/bin/env bash
docker-compose down &&
docker-compose up -d --build &&
docker-compose exec app php /app/bin/console game:start
#DOCKER_BUILDKIT=1 docker build -t php-roguelike . && docker run -it -e "TERM=xterm-256color" php-roguelike