SHELL := /bin/bash

up:
	docker compose up -d

down:
	docker compose down --remove-orphans

logs:
	docker compose logs -f --tail=200

bash:
	docker compose exec backend-php sh

env-copy:
	[ -f .env ] || cp .env.dist .env || true
	[ -f frontend/.env ] || cp frontend/.env.dist frontend/.env || true
	docker compose run --rm backend-php sh -lc '\
		cd /var/www/html; \
		if [ ! -f .env ]; then \
			if [ -f .env.dist ]; then cp .env.dist .env; \
			elif [ -f .env.example ]; then cp .env.example .env; fi; \
		fi'

init:
	$(MAKE) env-copy
	docker compose build backend-php
	$(MAKE) up
	$(MAKE) backend-new
	$(MAKE) fix-perms
	$(MAKE) backend-migrate
	$(MAKE) cache-clear
	$(MAKE) seed-csv
	$(MAKE) frontend-install

backend-new:
	docker compose run --rm backend-php bash -lc '\
set -e; cd /var/www/html; \
if [ -f artisan ]; then \
  if [ ! -f vendor/autoload.php ]; then \
    composer install --no-interaction --prefer-dist; \
  fi; \
else \
  TMP=$$(mktemp -d); \
  composer create-project laravel/laravel:^12.0 $$TMP; \
  cd $$TMP && tar cf - . | (cd /var/www/html && tar xf -); \
  rm -rf $$TMP; \
fi; \
php -r "file_exists(\".env\") || copy(file_exists(\".env.dist\")?\".env.dist\":\".env.example\", \".env\");"; \
php artisan key:generate --ansi || true; \
php artisan storage:link || true; \
'

backend-migrate:
	docker compose exec backend-php php artisan migrate

seed:
	docker compose exec backend-php php artisan migrate:fresh --seed

seed-csv:
	docker compose exec backend-php php artisan db:seed --class=Database\\Seeders\\HouseSeeder

test:
	docker compose exec backend-php php artisan test

test-feature:
	docker compose exec backend-php php artisan test --testsuite=Feature --compact

frontend-install:
	docker compose run --rm frontend sh -lc 'npm install'

lint:
	docker compose exec backend-php vendor/bin/pint -v --test

format:
	docker compose exec backend-php vendor/bin/pint -v

stan:
	docker compose exec backend-php vendor/bin/phpstan analyse --memory-limit=1G

qa:
	$(MAKE) lint && $(MAKE) stan && $(MAKE) test-feature

cache-clear:
	docker compose exec backend-php php artisan optimize:clear || true
	docker compose exec backend-php php -r 'function_exists("opcache_reset") && opcache_reset();' || true

rebuild:
	docker compose build --no-cache backend-php

fix-perms:
	docker compose exec backend-php bash -lc '\
	cd /var/www/html && \
	mkdir -p storage/framework/{cache,data,sessions,testing,views} storage/logs bootstrap/cache && \
	chown -R www-data:www-data storage bootstrap/cache && \
	chmod -R ug+rwX storage bootstrap/cache \
	'
