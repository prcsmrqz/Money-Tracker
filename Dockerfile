# Base PHP image with FPM
FROM php:8.2-fpm

# Install system dependencies + PHP extensions + Node.js
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libonig-dev libxml2-dev libzip-dev curl \
    && curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy package.json and package-lock.json first (for caching)
COPY package*.json ./

# Install Node dependencies
RUN npm install

# Copy the rest of the Laravel project files
COPY . .

# Build frontend with Vite
RUN npm run build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache public \
    && chmod -R 775 storage bootstrap/cache public

# Expose the port
EXPOSE $PORT

# Clear caches and run Laravel
CMD php artisan config:clear \
    && php artisan cache:clear \
    && php artisan view:clear \
    && php artisan migrate --force \
    && php artisan serve --host=0.0.0.0 --port=$PORT
