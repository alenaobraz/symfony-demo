#!/usr/bin/env bash

commands=(
    "prepare"
    "all"
    "one"
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

  if [[ ${cmd} == "prepare" ]]; then
    echo $cmd
    ./console.sh "doctrine:database:drop --if-exists --env=test --force -vvv"
    ./console.sh "--env=test doctrine:database:create"
    ./console.sh "--env=test doctrine:schema:create"
  elif [[ ${cmd} == "all" ]]; then
    ./scripts/exec.sh --container php-fpm --workdir "/opt" --command "php ./webapp/vendor/bin/phpunit --configuration webapp/phpunit.dist.xml"
  elif [[ ${cmd} == "one" ]]; then
      read -p "Enter full path to test: " fullpath
      ./scripts/exec.sh --container php-fpm --workdir "/opt" --command "php ./webapp/vendor/bin/phpunit --configuration webapp/phpunit.dist.xml ${fullpath}"
  fi