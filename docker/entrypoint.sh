#!/usr/bin/env sh
set -eu

: "${PORT:=10000}"
export PORT

sed -i "s/listen 80/listen ${PORT}/i" /etc/apache2/ports.conf

php artisan config:clear --no-interaction
php artisan route:clear --no-interaction
php artisan view:clear --no-interaction

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force --no-interaction
fi

php artisan config:cache --no-interaction
php artisan view:cache --no-interaction

exec "$@"
