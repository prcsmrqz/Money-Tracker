# Base PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies + Nginx
RUN apt-get update && apt-get install -y \
    nginx \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies and optimize Laravel
RUN composer install --no-dev --optimize-autoloader && \
    php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Remove default Nginx config and copy ours
RUN rm /etc/nginx/conf.d/default.conf
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Expose port 80
EXPOSE 80

# Run migrations, then start PHP-FPM and Nginx
CMD php artisan migrate --force && php-fpm -D && nginx -g 'daemon off;'
