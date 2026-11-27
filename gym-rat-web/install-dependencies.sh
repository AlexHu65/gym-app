#!/bin/sh
set -eu

for manifest in composer.json composer.lock package.json package-lock.json
do
    if [ ! -f "$manifest" ]; then
        printf 'Falta %s en gym-rat-web. Inicializa el proyecto y versiona sus archivos de bloqueo.\n' "$manifest" >&2
        exit 1
    fi
done

composer install --no-interaction --prefer-dist
npm ci
