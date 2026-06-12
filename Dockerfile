ARG PHP_VERSION=8.4

FROM php:${PHP_VERSION}-cli AS base

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=mlocati/php-extension-installer:latest /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions mongodb

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Set environment variable for Composer
ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["tail", "-f", "/dev/null"]

FROM base AS dev

# Install XDebug extension
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

FROM base AS ci

# Copy composer files
COPY composer.json composer.lock* ./

# Install dependencies including dev dependencies for testing
RUN composer install --optimize-autoloader

# Copy application code
COPY . .
