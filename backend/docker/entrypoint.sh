#!/bin/sh
set -e

mkdir -p /var/www/html/storage/logs \
         /var/www/html/storage/framework/{cache,sessions,testing,views} \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R ug+rwX           /var/www/html/storage /var/www/html/bootstrap/cache

rm -f /var/www/html/bootstrap/cache/*.php || true

exec "$@"
