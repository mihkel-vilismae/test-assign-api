# Use the official PHP image with FPM
FROM php:8.2-fpm

# Set the working directory
WORKDIR /var/www/html

# Copy application files to the container
COPY . /var/www/html

# Install required PHP extensions
#RUN apt-get update
#RUN apt-get install  mariadb-server-core mariadb-client -y
RUN docker-php-ext-install pdo_mysql

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]