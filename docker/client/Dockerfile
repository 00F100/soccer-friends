FROM composer/composer

FROM php:8.0-apache

RUN a2enmod rewrite \
    && apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        libzip-dev \
        libpq-dev

RUN apt-get install -y nodejs

RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && sed -i 's!/var/www/html!/var/www/html/soccer-friends/public!g' /etc/apache2/sites-available/000-default.conf \
    && useradd -u 1000 -m -s /bin/bash php

USER php

EXPOSE 80 9000