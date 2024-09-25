#!/bin/sh

# Ajuster les permissions
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Démarrer PHP-FPM
php-fpm -D

# Démarrer Nginx
nginx -g 'daemon off;'