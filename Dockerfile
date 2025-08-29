# Base PHP image (CLI for artisan serve)
FROM php:8.2-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies and clear caches
RUN composer install --no-dev --optimize-autoloader && \
    php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# Expose port Render expects
EXPOSE 8080

# Run migrations, then start Laravel server
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080
