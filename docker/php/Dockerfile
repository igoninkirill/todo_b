FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    supervisor \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libpng-dev \
    postgresql-client

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions amqp

RUN docker-php-ext-install pdo_pgsql zip exif

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD . /var/www/html
WORKDIR /var/www/html

COPY ./supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
