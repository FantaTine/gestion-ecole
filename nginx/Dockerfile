# Utiliser l'image officielle de Nginx
FROM nginx:alpine

# Copier le fichier de configuration personnalisé de Nginx
 COPY default.conf /etc/nginx/conf.d/default.conf

# Exposer le port 80 pour le serveur Nginx
EXPOSE 80

# Commande de démarrage Nginx
CMD ["nginx", "-g", "daemon off;"]