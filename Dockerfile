# Usamos una imagen base que ya tiene Apache y PHP instalados
FROM php:7.4-apache

# Habilitamos el módulo mod_rewrite de Apache
RUN a2enmod rewrite

# Configuramos el ServerName para Apache
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Copiamos el archivo .htaccess al directorio público de Apache
COPY .htaccess /var/www/html/

# Copiamos el resto de nuestros archivos de la aplicación al directorio público de Apache
COPY . /var/www/html/

# Exponemos el puerto 80 para que podamos acceder a nuestro sitio web
EXPOSE 80