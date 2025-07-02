#!/usr/bin/env bash

commands=(
    "update"
    "install"
    "run-script post-update-cmd"
    "run-script post-install-cmd"
    "require"
    "require --dev"
    "run-script pre-test"
    "run-script test"
)

if [[ -z $1 ]]; then
    PS3='Выберите команду: '
    select opt in "${commands[@]}"; do
        case "$opt" in
        *)
            cmd=${opt}
            break
            ;;
        esac
    done
else
    cmd=$1
fi

if [[ ${cmd} == "require" || ${cmd} == "require --dev" ]]; then
    read -p "package: " package
    read -p "version: " version
    options="${package} ${version}"
else
    options=""
fi

./scripts/exec.sh --container php-fpm --workdir "/opt/webapp" --env COMPOSER_MEMORY_LIMIT=-1 --command "composer ${cmd} ${options}"
