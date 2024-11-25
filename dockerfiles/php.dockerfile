FROM php:8.2-fpm-alpine

# Configurando o diretório de trabalho
WORKDIR /var/www/html

# Copiando os arquivos do projeto
COPY ./ /var/www/html

# Instalando dependências necessárias para extensões PHP
RUN apk add --no-cache \
    mysql-client \
    msmtp \
    perl \
    wget \
    procps \
    shadow \
    libzip \
    libpng \
    libjpeg-turbo \
    libwebp \
    freetype \
    icu \
    libxml2

# Instalando extensões PHP
RUN apk add --no-cache --virtual .build-deps \
    icu-dev \
    zlib-dev \
    g++ \
    make \
    autoconf \
    libzip-dev \
    libpng-dev \
    libwebp-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libxml2-dev && \
    docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install gd mysqli pdo_mysql intl bcmath opcache exif zip dom && \
    apk del .build-deps && \
    rm -rf /usr/src/php*

# Instalando o Redis via PECL
RUN apk add --no-cache pcre-dev $PHPIZE_DEPS && \
    pecl install redis && \
    docker-php-ext-enable redis

# Criando o usuário Laravel e ajustando permissões
RUN addgroup -g 1000 laravel && \
    adduser -G laravel -g laravel -s /bin/sh -D laravel && \
    chown -R laravel /var/www/html

# Definindo o usuário padrão
USER laravel
