FROM php:fpm

# Install necessary extensions for Laravel
RUN apt-get update && \
    apt-get install -y libzip-dev zip unzip && \
    docker-php-ext-install pdo_mysql zip

# Install MongoDB PHP extension
RUN pecl install mongodb && \
    docker-php-ext-enable mongodb

# Install Redis PHP extension
RUN pecl install redis && \
    docker-php-ext-enable redis

# Install PostgreSQL PHP extension
RUN apt-get install -y libpq-dev && \
    docker-php-ext-install pdo_pgsql

# Download and install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set up Nginx as the web server
RUN apt-get update && apt-get install -y nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the Laravel app files to the working directory
COPY . /var/www/html

RUN composer update --ignore-platform-req=ext-sockets --no-plugins --no-scripts
# php ini dir -> /usr/local/etc/php

RUN chown -R www-data: /var/www/html
RUN chmod -R 755 /var/www/html/storage
RUN chmod -R 755 /var/www/html/public

# Expose port 80 for Nginx
EXPOSE 80
RUN nginx -t
# Start Nginx and PHP-FPM
CMD service nginx start && php-fpm