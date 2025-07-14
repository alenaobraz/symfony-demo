#!/usr/bin/env bash

commands=(
    "symfony"
    "doctrine:cache:clear-metadata"
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

if [[ ${cmd} == "symfony" ]]; then
  docker exec -it $(docker ps -q --filter name=php-fpm) sh -c "cd /opt/webapp && php bin/console cache:clear && chmod 777 -R /opt/webapp/var/cache"
else
  ./scripts/exec.sh --container php-fpm --workdir "/opt/webapp" --command "php bin/console ${cmd}"
fi