FROM php:8.4-apache

# 1. Habilitar mod_rewrite
RUN a2enmod rewrite

# 2. Instalar extensiones
RUN docker-php-ext-install pdo_mysql

# 3. Configurar Apache para apuntar a la carpeta /public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# 4. Forzar AllowOverride All para que lea tu .htaccess dentro de /public
RUN sed -i '/<Directory \/var\/www\/html\/public>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# 5. Copiar TODO el código a /var/www/html/
COPY . /var/www/html/

# 6. Instalar dependencias
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 7. Asegurar permisos finales
RUN chown -R www-data:www-data /var/www/html/