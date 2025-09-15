# Usamos PHP con SQLite y Apache
FROM php:8.2-apache
#FROM nexus.42barcelona.com:5000/Lo que sea
# Instala SQLite y extensiones necesarias
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    curl\
    unzip\
    && docker-php-ext-install pdo pdo_sqlite

# Habilita mod_rewrite de Apache
RUN a2enmod rewrite

# Carpeta de trabajo en el contenedor
WORKDIR /var/www/html

# Copia el código de tu proyecto
COPY ./controller ./controller
COPY ./model ./model
COPY ./.htaccess ./
# Opcional: crea carpeta para DB si la quieres persistente
RUN mkdir -p /var/www/html/data

RUN curl -L -o /tmp/phpmailer.zip https://github.com/PHPMailer/PHPMailer/archive/refs/heads/master.zip \
    && unzip /tmp/phpmailer.zip -d /tmp \
    && mkdir -p /var/www/html/vendor \
    && mv /tmp/PHPMailer-master /var/www/html/vendor/phpmailer \
    && rm /tmp/phpmailer.zip

# Da permisos apropiados (Apache)
RUN chown -R www-data:www-data /var/www/html

# Exponer puerto 80 para Apache
EXPOSE 80


# Comando por defecto
CMD apache2-foreground

