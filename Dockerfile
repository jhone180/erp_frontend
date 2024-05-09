# Use the official PHP image from the dockerhub
FROM php:8.1-apache

# Update system packages and install composer and necessary PHP extensions
RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y git libonig-dev zlib1g-dev libpng-dev && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Enable apache modules
RUN a2enmod rewrite headers

# Copy application source
COPY . /var/www/html

# Allow Composer to run as superuser
ENV COMPOSER_ALLOW_SUPERUSER 1

# Install PHP dependencies
RUN composer install

# Change current user to www
USER www-data

# By default, .htaccess is ignored. Enable .htaccess through apache config
RUN echo '<Directory "/var/www/html">\n\
    AllowOverride All\n\
</Directory>\n'\
>> /etc/apache2/apache2.conf

# Expose port 8080 and start apache server in the foreground
CMD ["apache2-foreground"]