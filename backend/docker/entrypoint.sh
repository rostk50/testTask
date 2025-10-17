#!/bin/sh
set -e

umask 0002

mkdir -p \
  /var/www/html/storage/logs \
  /var/www/html/storage/framework/cache/data \
  /var/www/html/storage/framework/sessions \
  /var/www/html/storage/framework/testing \
  /var/www/html/storage/framework/views \
  /var/www/html/bootstrap/cache

touch /var/www/html/storage/logs/laravel.log || true

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R ug+rwX           /var/www/html/storage /var/www/html/bootstrap/cache
chmod 664 /var/www/html/storage/logs/laravel.log || true

rm -f /var/www/html/bootstrap/cache/*.php || true

exec "$@"
