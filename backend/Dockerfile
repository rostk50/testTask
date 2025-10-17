FROM php:8.4-fpm-alpine
RUN apk add --no-cache bash icu-dev libpq-dev libzip-dev oniguruma-dev git unzip autoconf g++ make
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo_pgsql bcmath mbstring intl zip opcache
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY docker/entrypoint.sh /usr/local/bin/app-entrypoint
RUN chmod +x /usr/local/bin/app-entrypoint
ENTRYPOINT ["app-entrypoint"]
CMD ["php-fpm"]
