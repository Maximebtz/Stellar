#!/bin/bash
set -e

# Ajuster les permissions uniquement pour le répertoire var
chown -R www-data:www-data /var/www/html/var
find /var/www/html/var -type d -exec chmod 755 {} \;
find /var/www/html/var -type f -exec chmod 644 {} \;

# Assurez-vous que le cache et les logs sont accessibles en écriture
chmod -R 777 /var/www/html/var/cache
chmod -R 777 /var/www/html/var/log

apache2-foreground
