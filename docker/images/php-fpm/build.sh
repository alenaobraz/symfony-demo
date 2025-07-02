#!/usr/bin/env bash

docker build \
    --pull \
    -f Dockerfile \
    -t alenaobraz/php:8.4-fpm-alpine3.21 \
    .
