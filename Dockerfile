# Use the official PHP image with extensions
FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    unzip \
    git \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip gd mbstring exif pcntl bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www

# Install project dependencies
RUN composer install --no-scripts --no-autoloader

# Create firebase-key.json from environment variable
RUN mkdir -p /var/www/storage/firebase && \
    if [ -n "$FIREBASE_CREDENTIALS" ]; then \
        echo $FIREBASE_CREDENTIALS | base64 -d > /var/www/storage/firebase/firebase-key.json; \
    else \
        echo "{}" > /var/www/storage/firebase/firebase-key.json; \
    fi

# Copy environment file
COPY .env.example .env

# Set permissions for storage and cache
RUN chown -R www-data:www-data /var/www/storage \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Run composer scripts and optimize
#RUN composer dump-autoload --optimize

# Generate application key
#RUN php artisan key:generate

# Expose port
EXPOSE $PORT

# Start the application
CMD php artisan serve --host=0.0.0.0 --port=$PORT