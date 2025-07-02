#!/usr/bin/env bash

commands=(
    "make:migration"
    "doctrine:migrations:migrate"
    "doctrine:migrations:migrate prev"
)

if [[ -z $1 ]]; then
    PS3='Выберите команду: '
    select opt in "${commands[@]}"; do
        case "$opt" in
        "Все")
            cmd="all"
            break
            ;;
        *)
            cmd=${opt}
            break
            ;;
        esac
    done
else
    cmd=$1
fi

./scripts/exec.sh --container php-fpm --workdir "/opt/webapp" --command "php bin/console ${cmd}"
