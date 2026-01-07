FROM php:8.2-apache

# Ensure only ONE Apache MPM is enabled
RUN a2dismod mpm_event mpm_worker || true \
    && a2enmod mpm_prefork

RUN a2enmod rewrite
RUN docker-php-ext-install pdo_mysql mysqli

ENV APACHE_DOCUMENT_ROOT=/var/www/html/student-grades/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www/html