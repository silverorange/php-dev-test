# syntax=docker/dockerfile:1

# https://docs.docker.com/go/dockerfile-reference/

FROM php:8.2-fpm

RUN apt-get update

RUN apt-get install -y --no-install-recommends \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

# Install Composer
RUN apt-get install -y --no-install-recommends unzip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clean up
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
