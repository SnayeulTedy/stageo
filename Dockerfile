# Dockerfile pour Stagiel (PHP)
FROM php:8.3-apache

# Installation des extensions PHP nécessaires
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activation du module rewrite d'Apache
RUN a2enmod rewrite

# Copie tous les fichiers du projet dans le container
COPY . /var/www/html/

# Donner les permissions nécessaires
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Exposer le port
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]