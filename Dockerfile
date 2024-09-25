# Utiliser une image officielle de PHP avec FPM (FastCGI Process Manager)
FROM php:8.3-fpm

# Installer les dépendances du système
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip bcmath opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Créer un utilisateur non-root
RUN useradd -m myuser

# Copier le contenu de l'application dans le conteneur
COPY --chown=myuser:myuser . /var/www

# Définir le répertoire de travail
WORKDIR /var/www

# Configurer les variables d'environnement pour Laravel
ENV APP_ENV=production
ENV APP_DEBUG=false

# Installer les dépendances PHP avec Composer en tant qu'utilisateur non-root
USER myuser
RUN composer install --no-scripts --no-autoloader
USER root

# Générer la clé d'application Laravel
RUN php artisan key:generate

# Optimiser Laravel pour la production
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Donner les permissions adéquates aux répertoires de stockage et de cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Copier le fichier de configuration Nginx
COPY nginx/default.conf /etc/nginx/sites-available/default
RUN rm -f /etc/nginx/sites-enabled/default && \
    ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Exposer les ports pour PHP-FPM et Nginx
EXPOSE 9000

# Copier et exécuter le script de démarrage
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Lancer le script de démarrage quand le conteneur démarre
CMD ["sh", "/usr/local/bin/start.sh"]