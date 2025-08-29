# Base PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies + PHP extensions + Node.js
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libonig-dev libxml2-dev libzip-dev curl \
    nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build frontend (Vite)
RUN npm install && npm run build

# Set permissions for storage, cache, and public directories
# 'public' is added to ensure the web server can serve the compiled assets
RUN chown -R www-data:www-data storage bootstrap/cache public \
    && chmod -R 775 storage bootstrap/cache public

# Expose port (Render / Railway provides $PORT)
EXPOSE 10000

# Run migrations and start Laravel server
# This single CMD command combines all necessary runtime steps
CMD php artisan config:clear \
    && php artisan cache:clear \
    && php artisan view:clear \
    && php artisan migrate --force \
    && php artisan serve --host=0.0.0.0 --port=$PORT