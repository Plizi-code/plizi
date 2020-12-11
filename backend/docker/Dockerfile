FROM kiliman6aro/php-fpm:7.3

ENV PHPREDIS_VERSION 3.0.0

RUN mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install redis

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

RUN docker-php-ext-install bcmath

RUN wget https://getcomposer.org/installer --no-check-certificate \
        && php installer \
        && ln -s /composer.phar /usr/bin/composer

WORKDIR /app

COPY ./php/fpm_www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./php/php.ini /usr/local/etc/php/

CMD ["php-fpm"]
