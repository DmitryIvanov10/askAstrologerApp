FROM library/php:7.4.6-fpm-alpine
RUN apk --update add wget \
		     curl \
		     git \
		     php7 \
		     php7-curl \
		     php7-openssl \
		     php7-iconv \
		     php7-json \
		     php7-mbstring \
		     php7-phar \
		     php7-dom --repository http://nl.alpinelinux.org/alpine/edge/testing/ && rm /var/cache/apk/*

# add php mysql extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# add ext-intl
RUN apk add --no-cache icu-dev \
    && docker-php-ext-install intl

RUN chown -R www-data:www-data /var/www/html
RUN apk update && \
    apk add --no-cache --virtual dev-deps git autoconf gcc g++ make && \
    apk add --no-cache zlib-dev libzip-dev libmemcached-dev icu-dev bash

# install xDebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN echo "xdebug.remote_host=192.168.220.1" >> /usr/local/etc/php/conf.d/default.ini \
    && echo "serverName=app.local" >> /usr/local/etc/php/conf.d/default.ini \
    && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/default.ini \
    && echo "xdebug.remote_autostart=0" >> /usr/local/etc/php/conf.d/default.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/default.ini \
    && echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/default.ini

ADD ./php.ini /usr/local/etc/php/php.ini

# install composer
RUN curl -sS https://getcomposer.org/installer | php \
        && mv composer.phar /usr/local/bin/ \
        && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

EXPOSE 9000
