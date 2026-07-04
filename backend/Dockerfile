FROM php:8.5.7-fpm

RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libpq-dev

RUN docker-php-ext-install -j$(nproc) \
    pdo_pgsql

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer config --global repo.packagist composer https://packagist.org
ENV PATH="$PATH:/root/.composer/vendor/bin"