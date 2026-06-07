FROM php:8.4-apache

# Instalar dependencias del sistema y la extensión pdo_mysql para conectarse a Cloud SQL
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql zip

# Habilitar mod_rewrite de Apache para que funcione el enrutador de tu MVC
RUN a2enmod rewrite

# Copiar el código del proyecto al contenedor
COPY . /var/www/html/

# Instalar Composer y descargar las dependencias del proyecto (incluyendo Phinx, Monolog, etc.)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Cambiar el propietario de los archivos a www-data (Apache)
RUN chown -R www-data:www-data /var/www/html/

# Cloud Run inyecta el puerto dinámicamente en la variable de entorno $PORT (por defecto 8080)
# Necesitamos configurar Apache para que escuche en ese puerto
ENV PORT=8080
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

EXPOSE 8080