FROM kiliman6aro/php-fpm:7.3

RUN apt-get update \
 && apt-get install -y build-essential libtool autoconf uuid-dev pkg-config git libsodium-dev wget bzip2 \
 && wget https://archive.org/download/zeromq_4.1.4/zeromq-4.1.4.tar.gz \
 && tar -xvzf zeromq-4.1.4.tar.gz \
 && cd zeromq-4.1.4 \
 && ./configure \
 && make \
 && make install \
 && ldconfig \
 && cd .. \
 && rm -fr zeromq-4.1.4.tar.gz zeromq-4.1.4/ \
 && git clone git://github.com/mkoppanen/php-zmq.git \
 && cd php-zmq \
 && phpize && ./configure \
 && make \
 && make install \
 && cd .. \
 && rm -fr php-zmq \
 && echo "extension=zmq.so" > /usr/local/etc/php/conf.d/docker-php-ext-zmq.ini \
 && apt-get -y remove git build-essential \
 && apt-get -y autoremove --purge \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

WORKDIR /app

EXPOSE 5555

CMD ["php", "artisan", "ws:serve"]
