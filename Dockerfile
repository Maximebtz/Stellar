# Étape 1 : Télécharger une image de PHP avec Apache
FROM php:8.2-apache

# Mettre à jour les paquets et installer les dépendances
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    curl

# Nettoyer le cache APT
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configurer et installer les extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install gd intl mbstring pdo pdo_mysql zip opcache

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurer le répertoire de travail
WORKDIR /var/www/html

# Copier tous les fichiers de l'application Symfony
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html

# Ajouter ces lignes pour s'assurer que Apache peut lire le fichier .htaccess
RUN chmod -R 755 /var/www/html
RUN a2enmod rewrite

# Installer les dépendances PHP avec Composer
RUN composer install --optimize-autoloader

# Configurer Apache et permissions
RUN a2enmod rewrite expires
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Exposer le port 80
EXPOSE 80

# Copier et rendre exécutable le script d'entrée
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Lancer le script d'entrée
CMD ["docker-entrypoint.sh"]