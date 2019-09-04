FROM php:7.1-fpm
EXPOSE 9000
WORKDIR /app

ARG UID
ARG GID
ENV UID $UID
ENV GID $GID
ENV XDEBUGINI_PATH=/usr/local/etc/php/conf.d/xdebug.ini

RUN addgroup --gid ${GID} dockergroup && \
    useradd -m -u ${UID} -g ${GID} dockeruser

RUN docker-php-ext-install bcmath pdo pdo_mysql && \
    apt-get update && \
    apt-get install -y zip \
    unzip \
    git && \
    apt-get update && \
    curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

RUN mkdir /home/dockeruser/.composer && \
    chown -R ${UID}:${GID} /home/dockeruser/.composer

RUN pecl install xdebug-2.5.5 && docker-php-ext-enable xdebug
#RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" >> /usr/local/etc/php/php.ini

#RUN echo "xdebug.idekey = PHPSTORM" >> /etc/php/7.1/fpm/conf.d/20-xdebug.ini
#RUN echo "xdebug.default_enable = 0" >> /etc/php/7.1/fpm/conf.d/20-xdebug.ini
#RUN echo "xdebug.remote_enable = 1" >> /etc/php/7.1/fpm/conf.d/20-xdebug.ini
#RUN echo "xdebug.remote_autostart = 0" >> /etc/php/7.1/fpm/conf.d/20-xdebug.ini
#RUN echo "xdebug.remote_connect_back = 0" >> /etc/php/7.1/fpm/conf.d/20-xdebug.ini
#RUN echo "xdebug.profiler_enable = 0" >> /etc/php/7.1/fpm/conf.d/20-xdebug.ini
#RUN echo "xdebug.remote_host = 127.0.0.1" >> /etc/php/7.1/fpm/conf.d/20-xdebug.ini
#RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini
#RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;
#RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;
#RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;
#RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini;
#RUN echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo 'zend_extension="/usr/local/lib/php/extensions/no-debug-non-zts-20151012/xdebug.so"' >> /usr/local/etc/php/php.ini
#RUN echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/php.ini
#RUN echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/php.ini
#RUN echo 'xdebug.remote_connect_back=1' >> /usr/local/etc/php/php.ini

CMD composer install && \
    composer dump-autoload && \
    php bin/console doctrine:migrations:migrate --no-interaction && \
    php-fpm
