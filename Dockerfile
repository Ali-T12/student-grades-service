FROM php:8.2-apache

# HARD reset: remove any enabled MPM modules then enable only prefork
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork \
    && a2dismod mpm_event mpm_worker || true

RUN a2enmod rewrite

RUN docker-php-ext-install pdo pdo_mysql mysqli

ENV APACHE_DOCUMENT_ROOT=/var/www/html/student-grades/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www/html

# (اختياري للتأكيد أثناء البناء)
RUN apachectl -M | grep mpm || true