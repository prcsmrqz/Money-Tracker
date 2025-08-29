# Stage 1: Build all assets and dependencies
FROM php:8.2-fpm AS build

WORKDIR /var/www/html

# Install system dependencies + PHP extensions + Node.js
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libonig-dev libxml2-dev libzip-dev curl \
    nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy project files and install dependencies
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build frontend (Vite)
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Stage 2: Final production image with Nginx and PHP-FPM
FROM php:8.2-fpm AS final

# Install Nginx and other essential dependencies
RUN apt-get update && apt-get install -y \
    nginx git unzip libonig-dev libxml2-dev libzip-dev curl \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy the built application from the 'build' stage
COPY --from=build /var/www/html /var/www/html

# Copy the Nginx configuration (assuming it's named nginx.conf)
COPY nginx.conf /etc/nginx/sites-available/default

# Create a symlink for Nginx to enable the config
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Correctly configure PHP-FPM to listen on a TCP socket for Nginx to connect to
RUN sed -i 's/listen = \/run\/php\/php8.2-fpm.sock/listen = 127.0.0.1:9000/' /etc/php/8.2/fpm/pool.d/www.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose Nginx's port
EXPOSE 80

# Start both Nginx and PHP-FPM
CMD service php8.2-fpm start && nginx -g 'daemon off;'