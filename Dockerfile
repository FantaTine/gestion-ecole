FROM php:8.3-fpm

   # Install system dependencies
   RUN apt-get update && apt-get install -y \
       git \
       curl \
       libpng-dev \
       libonig-dev \
       libxml2-dev \
       zip \
       unzip \
       nodejs \
       npm \
       libpq-dev

   # Clear cache
   RUN apt-get clean && rm -rf /var/lib/apt/lists/*

   # Install PHP extensions
   RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

   # Get latest Composer
   COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

   # Set working directory
   WORKDIR /var/www/html/laravel/gestion-ecole

   # Copy existing application directory contents
   COPY . /var/www/html/laravel/gestion-ecole

   # Install dependencies
   RUN composer install --no-scripts --no-autoloader

   # Generate autoloader
   RUN composer dump-autoload

   # Copy existing application directory permissions
   COPY --chown=www-data:www-data . /var/www/html/laravel/gestion-ecole

   # Ensure the Firebase credentials directory exists
   RUN mkdir -p /var/www/html/laravel/gestion-ecole/storage/firebase && \
       chown -R www-data:www-data /var/www/html/laravel/gestion-ecole/storage/firebase

   # Change current user to www-data
   USER www-data

   # Expose port 9000 and start php-fpm server
   EXPOSE 9000
   CMD ["php-fpm"]