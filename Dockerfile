FROM php:8.2-apache

# Disable all MPMs first
RUN a2dismod mpm_event mpm_worker || true

# Enable prefork MPM
RUN a2enmod mpm_prefork

# Enable rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Set Apache document root
ENV APACHE_DOCUMENT_ROOT=/var/www/html/student-grades/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www/html