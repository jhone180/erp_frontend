# Usamos una imagen base que ya tiene Apache y PHP instalados
FROM php:7.4-apache

# Habilitamos el módulo mod_rewrite de Apache
RUN a2enmod rewrite

# Configuramos el ServerName para Apache
RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

# Instalamos las dependencias necesarias para las herramientas que usas
RUN apt-get update && apt-get install -y \
    npm \
    nodejs \
    python3 \
    python3-pip \
    ruby \
    ruby-dev \
    ruby-bundler

# Copiamos el archivo .htaccess al directorio público de Apache
COPY .htaccess /var/www/html/

# Copiamos el resto de nuestros archivos de la aplicación al directorio público de Apache
COPY . /var/www/html/

# Instalamos las dependencias de Composer
COPY composer.json composer.lock /var/www/html/
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install

# Instalamos las dependencias de npm
COPY package.json package-lock.json /var/www/html/
RUN npm install

# Instalamos las dependencias de Bundler
COPY Gemfile Gemfile.lock /var/www/html/
RUN bundle install

# Instalamos las dependencias de pip
COPY requirements.txt /var/www/html/
RUN pip3 install -r requirements.txt

# Exponemos el puerto 80 para que podamos acceder a nuestro sitio web
EXPOSE 80