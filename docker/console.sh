#!/usr/bin/env bash

./scripts/exec.sh --container php-fpm --workdir "/opt/webapp" --command "php bin/console $*"