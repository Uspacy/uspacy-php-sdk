#Use pre-builded container
FROM ghcr.io/uspacy/laravel-core-docker:main

ARG SERVICE_NAME
ARG DD_ENV

RUN curl -LO https://github.com/DataDog/dd-trace-php/releases/latest/download/datadog-setup.php
RUN php datadog-setup.php --php-bin=all --enable-profiling
RUN echo "env[DD_SERVICE] = $SERVICE_NAME" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "env[DD_ENV] = $DD_ENV" >> /usr/local/etc/php-fpm.d/www.conf \
    && echo "env[DD_PROFILING_ENABLED] = true" >> /usr/local/etc/php-fpm.d/www.conf

MAINTAINER ms@alterego.digital

### CONST ENV
ENV COMPOSER_MEMORY_LIMIT='-1'
###
ARG COMPOSER_AUTH

#Copy code
COPY ./ ./
# Fix permissions
RUN chown -R app.app ./

#Switch user
USER app

#Fix storage directories
RUN mkdir -p storage/framework/sessions
RUN mkdir -p storage/framework/views
RUN mkdir -p storage/framework/cache
# Install packeges from composer
RUN composer install
RUN php artisan route:cache && composer dump-autoload