# Usamos PHP con SQLite y Apache
FROM php:8.2-apache

# Instala SQLite y extensiones necesarias
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

# Habilita mod_rewrite de Apache
RUN a2enmod rewrite

# Carpeta de trabajo en el contenedor
WORKDIR /var/www/html

# Copia el código de tu proyecto
COPY ./controller ./controller
COPY ./model ./model

# Opcional: crea carpeta para DB si la quieres persistente
RUN mkdir -p /var/www/html/data

# Da permisos apropiados (Apache)
RUN chown -R www-data:www-data /var/www/html

# Exponer puerto 80 para Apache
EXPOSE 80

# Comando por defecto
CMD ["apache2-foreground"]
