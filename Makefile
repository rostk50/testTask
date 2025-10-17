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
	$(MAKE) backend-migrate
	$(MAKE) seed
	$(MAKE) frontend-install

backend-new:
	docker compose run --rm backend-php bash -lc '\
set -e; \
if [ ! -f artisan ]; then \
  TMP=$$(mktemp -d); \
  composer create-project laravel/laravel:^12.0 $$TMP; \
  cd $$TMP && tar cf - . | (cd /var/www/html && tar xf -); \
  rm -rf $$TMP; \
fi; \
php -r "file_exists(\".env\") || copy(\".env.example\", \".env\");"; \
php artisan key:generate; \
sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=pgsql/" .env; \
sed -i "s/^DB_HOST=.*/DB_HOST=db/" .env; \
sed -i "s/^DB_PORT=.*/DB_PORT=5432/" .env; \
sed -i "s/^DB_DATABASE=.*/DB_DATABASE=app/" .env; \
sed -i "s/^DB_USERNAME=.*/DB_USERNAME=app/" .env; \
sed-i "s/^DB_PASSWORD=.*/DB_PASSWORD=app/" .env || sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=app/" .env; \
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
	docker compose exec backend-php php artisan optimize:clear
	docker compose exec backend-php php -r 'opcache_reset();'

rebuild:
	docker compose build --no-cache backend-php