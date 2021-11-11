#!/usr/bin/env bash
DOCKER_BUILDKIT=1 docker build -t php-roguelike . && docker run -it -e "TERM=xterm-256color" php-roguelike