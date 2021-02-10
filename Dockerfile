FROM php:7.3-fpm

RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list \
    && apt update \
    && apt install -y --no-install-recommends \
        default-mysql-client \
        git \
        libmagickwand-dev \
        libpng-dev \
        libssl-dev \
        libxml2-dev \
        libzip-dev \
        nodejs \
        unzip \
        vim \
        yarn \
        zip \
        zlib1g-dev \
    && docker-php-ext-install bcmath exif gd pdo_mysql pcntl soap sockets zip > /dev/null \
    && docker-php-ext-configure zip --with-libzip \
    && pecl install imagick xdebug \
    && docker-php-ext-enable imagick xdebug \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli

RUN echo "if [ -f /root/.bash/.bash_aliases ]; then\n\t. /root/.bash/.bash_aliases\nfi" >> /root/.bashrc

WORKDIR /app
