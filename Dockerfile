FROM composer:latest as build

COPY . /usr/src/app
WORKDIR /usr/src/app

RUN composer install --no-dev --optimize-autoloader

FROM php:8.1-fpm-buster

RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) iconv mbstring gd pdo pdo_mysql zip

COPY --from=build /usr/src/app /var/www/html

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
