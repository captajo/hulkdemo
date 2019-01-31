FROM php:7.1-apache

RUN docker-php-ext-install pdo_mysql

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

RUN apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libmcrypt-dev libpng-dev && docker-php-ext-install -j$(nproc) iconv mcrypt && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && docker-php-ext-install -j$(nproc) gd

 RUN apt-get update && apt-get install -y libmemcached-dev \
 && pecl install memcached \ 
 && docker-php-ext-enable memcached

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

ENV ELASTICSEARCH_HOST elasticseach
ENV ELASTICSEARCH_PORT 9200
ENV ELASTICSEARCH_SCHEME http

ENV CACHE_DRIVER memcached
ENV MEMCACHED_HOST memcache
ENV MEMCACHED_PORT 11211

ENV DB_CONNECTION mysql
ENV DB_HOST db
ENV DB_PORT 3306
ENV DB_DATABASE hulkshare
ENV DB_USERNAME user
ENV DB_PASSWORD test

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
