FROM php:8.2-apache

# Install required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy project files into Apache
COPY . /var/www/html/

# Permissions (optional but useful)
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
