# Base PHP image with FPM
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies + PHP extensions + Node.js
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libonig-dev libxml2-dev libzip-dev curl \
    && curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files
COPY . .

# Copy .env for frontend build
COPY .env .env

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set Node environment to production
ENV NODE_ENV=production

# Install Node dependencies and build frontend (Vite)
RUN npm install --legacy-peer-deps \
    && npm run build

# Set permissions for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache public \
    && chmod -R 775 storage bootstrap/cache public

# Expose the dynamic port provided by Render
EXPOSE $PORT

# Clear caches, migrate, and start Laravel server
CMD php artisan config:clear \
    && php artisan cache:clear \
    && php artisan view:clear \
    && php artisan migrate --force \
    && php artisan serve --host=0.0.0.0 --port=$PORT
