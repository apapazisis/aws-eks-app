FROM composer:2.10.2 AS deps

WORKDIR /app

COPY composer.json .
COPY composer.lock .

RUN composer install --prefer-dist --no-dev --no-autoloader --no-scripts

COPY . .

RUN composer dump-autoload --optimize --no-dev --no-scripts

#----------------------------------------------

FROM php:8.5.7-fpm AS project

RUN apt update && apt install -y \
    unzip \
    libzip-dev \
    libpq-dev

RUN docker-php-ext-install -j$(nproc) \
    pdo_pgsql

WORKDIR /app

COPY . .
COPY --from=deps /app/vendor ./vendor

RUN php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache

#----------------------------------------------

FROM project AS production

USER www-data

EXPOSE 9000

#----------------------------------------------

FROM project AS development

EXPOSE 9000