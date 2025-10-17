#!/bin/sh
set -euo pipefail

APP_DIR=/var/www/html

mkdir -p "$APP_DIR"/storage/{logs,app,framework/{cache,data,sessions,testing,views}}
mkdir -p "$APP_DIR"/bootstrap/cache

chown -R www-data:www-data "$APP_DIR"/storage "$APP_DIR"/bootstrap/cache
chmod -R ug+rwX "$APP_DIR"/storage "$APP_DIR"/bootstrap/cache

exec "$@"
