#!/usr/bin/env bash
DOCKER_BUILDKIT=1 docker build -t php-roguelike . && docker run -it php-roguelike