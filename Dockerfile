# Stage 1: Build PHP with dependencies
FROM php:8.2-fpm AS build

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader && \
    php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Stage 2: Nginx + PHP-FPM
FROM nginx:alpine

# Copy PHP binaries & app from build
COPY --from=build /usr/local/etc/php /usr/local/etc/php
COPY --from=build /usr/local/bin/php /usr/local/bin/php
COPY --from=build /usr/local/sbin/php-fpm /usr/local/sbin/php-fpm
COPY --from=build /usr/bin/composer /usr/bin/composer
COPY --from=build /var/www/html /var/www/html

# Copy Nginx configuration
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Set working directory
WORKDIR /var/www/html

# Expose port 80 for Render
EXPOSE 80

# Run migrations before starting the server
CMD php artisan migrate --force && php-fpm -D && nginx -g 'daemon off;'
