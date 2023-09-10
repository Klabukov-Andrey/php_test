FROM php:8.2-fpm

RUN docker-php-ext-install pdo mysqli pdo_mysql
RUN apt-get clean && apt-get update \
    && apt-get install -q -y --no-install-recommends \ 
        zlib1g-dev \
        libzip-dev \
        unzip \
        curl \
        git \
        wget
# Configure composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=2.3.5
RUN mkdir -p /var/www/.composer/cache/ && chown -R www-data /var/www/.composer/cache/
