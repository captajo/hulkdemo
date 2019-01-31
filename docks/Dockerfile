FROM php:7.1-apache

RUN docker-php-ext-install pdo_mysql
RUN apt-get update

RUN a2enmod rewrite

RUN chmod 755 -R /var/www/html

COPY . /var/www/html
RUN chmod -R o+w /var/www/html/storage

ENV APP_NAME HulkshareDemo
ENV APP_ENV local
ENV APP_KEY base64:iwhse7xKrDmzljAkKDo/1gtqkdxOY64JeRKNGIG3Ie8=
ENV APP_DEBUG true
ENV APP_LOG_LEVEL debug
ENV APP_URL http://localhost

ENV ELASTICSEARCH_HOST 34.212.11.82
ENV ELASTICSEARCH_PORT 80
ENV ELASTICSEARCH_SCHEME http

ENV DB_CONNECTION mysql
ENV DB_HOST localhost
ENV DB_PORT 3306
ENV DB_DATABASE hulkshare
ENV DB_USERNAME root
ENV DB_PASSWORD root

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
