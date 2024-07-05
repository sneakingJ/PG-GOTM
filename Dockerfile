FROM ghcr.io/serversideup/php:8.1-cli-alpine AS base

USER root

RUN set -eux && install-php-extensions intl

USER www-data

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]

##############################################################################

FROM base AS development

ARG WWWUSER
ARG WWWGROUP

USER root

RUN usermod -ou $WWWUSER www-data \
    && groupmod -og $WWWGROUP www-data \
    && useradd -mNo -g www-data -u $(id -u www-data) sail

USER www-data

##############################################################################

FROM node:18-alpine AS assets

WORKDIR /app

COPY --chown=www-data:www-data . /app

RUN npm run prod

##############################################################################

FROM base AS production

# Again, this is not to mess with permissions
COPY --chown=www-data:www-data . /var/www/html

RUN composer install --optimize-autoloader --no-dev

RUN sed -i 's/protected \$proxies/protected \$proxies = "*"/g' app/Http/Middleware/TrustProxies.php

COPY --from=assets /app/public /var/www/html/public