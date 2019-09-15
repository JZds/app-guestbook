FROM php:7.1-fpm
EXPOSE 9000
WORKDIR /app

ARG UID
ARG GID
ENV UID $UID
ENV GID $GID

RUN addgroup --gid ${GID} dockergroup && \
    useradd -m -u ${UID} -g ${GID} dockeruser

RUN docker-php-ext-install bcmath pdo pdo_mysql && \
    apt-get update && \
    apt-get install -y curl gnupg zip unzip git && \
    apt-get update && \
    curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

RUN mkdir -p ./node_modules && chown -R ${UID}:${GID} ./node_modules
COPY package*.json ./
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -
RUN apt-get install -y nodejs

RUN mkdir /home/dockeruser/.composer && \
    chown -R ${UID}:${GID} /home/dockeruser/.composer

CMD composer install && \
    composer dump-autoload && \
    php bin/console doctrine:migrations:migrate && \
    php-fpm
