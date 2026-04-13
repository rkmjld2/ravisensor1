FROM php:8.2-apache

# Install MySQLi
RUN docker-php-ext-install mysqli

# Enable Apache
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html/

# Permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80