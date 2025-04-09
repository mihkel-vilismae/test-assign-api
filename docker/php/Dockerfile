FROM php:8.1-fpm-alpine

# Install necessary extensions for MySQL
RUN apk add --no-cache mysql-client mysql-dev && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy your custom php.ini (if any)
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html