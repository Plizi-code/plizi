FROM phpdockerio/php73-cli

# Install FPM
RUN apt-get update \
    && apt-get -y --no-install-recommends install php7.3-fpm \
    && apt-get -y --no-install-recommends install php7.3-mbstring \
    && apt-get -y --no-install-recommends install php7.3-mysql \
    && apt-get -y --no-install-recommends install php7.3-intl \
    && apt-get -y --no-install-recommends install php7.3-json \
    && apt-get -y --no-install-recommends install php7.3-curl \
    && apt-get -y --no-install-recommends install php7.3-zip \
    && apt-get -y --no-install-recommends install php7.3-gmp \
    && apt-get -y --no-install-recommends install wget \
    && apt-get -y --no-install-recommends install php7.3-gd \
    && apt-get -y --no-install-recommends install php7.3-bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
&& sed -i "/listen = .*/c\listen = [::]:9000" /etc/php/7.3/fpm/pool.d/www.conf \
    && sed -i "/;access.log = .*/c\access.log = /proc/self/fd/1" /etc/php/7.3/fpm/pool.d/www.conf \
    && sed -i "/;clear_env = .*/c\clear_env = no" /etc/php/7.3/fpm/pool.d/www.conf \
    && sed -i "/;catch_workers_output = .*/c\catch_workers_output = yes" /etc/php/7.3/fpm/pool.d/www.conf \
    && sed -i "/pid = .*/c\;pid = /run/php/php7.3-fpm.pid" /etc/php/7.3/fpm/php-fpm.conf \
    && sed -i "/;daemonize = .*/c\daemonize = no" /etc/php/7.3/fpm/php-fpm.conf \
    && sed -i "/error_log = .*/c\error_log = /proc/self/fd/2" /etc/php/7.3/fpm/php-fpm.conf \
    && sed -i "/pm.max_children = .*/c\pm.max_children = 15" /etc/php/7.3/fpm/php-fpm.conf \
    && usermod -u 1000 www-data \
&& wget https://getcomposer.org/installer --no-check-certificate \
    && php installer \
    && ln -s /composer.phar /usr/bin/composer \
&& mkdir -p /var/www/.composer \
    && chown -R www-data /var/www/.composer/

# The following runs FPM and removes all its extraneous log output on top of what your app outputs to stdout
CMD /usr/sbin/php-fpm7.3 -F -O 2>&1 | sed -u 's,.*: \"\(.*\)$,\1,'| sed -u 's,"$,,' 1>&1

# Open up fcgi port
EXPOSE 9000
