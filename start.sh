#!/bin/sh

nginx -t

# Démarrer PHP-FPM
php-fpm -D

# Démarrer Nginx
nginx -g 'daemon off;'