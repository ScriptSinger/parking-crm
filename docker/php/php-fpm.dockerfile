FROM php:8.2-fpm

# Аргументы для UID/GID
ARG UID=1000
ARG GID=1000

# Логируем значения UID и GID
RUN echo "📦 UID: ${UID}, GID: ${GID}"

# Установка полезных утилит для отладки: ps, tree, curl
RUN apt-get update && apt-get install -y --no-install-recommends \
    procps \
    tree \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Установка необходимых PHP-расширений
RUN docker-php-ext-install \
    mysqli \
    pdo_mysql \
    pdo \
    pcntl \
    && docker-php-ext-enable mysqli pdo_mysql

# Установка расширения Redis через PECL
RUN pecl install redis && docker-php-ext-enable redis   

# Настройка PHP-FPM: меняем www-data → новый UID/GID
RUN sed -i "s/^user = .*/user = ${UID}/" /usr/local/etc/php-fpm.d/www.conf && \
    sed -i "s/^group = .*/group = ${GID}/" /usr/local/etc/php-fpm.d/www.conf


# Создание группы и пользователя с нужными UID/GID (нужно для artisan)
RUN groupadd -g ${GID} phpuser && \
    useradd -u ${UID} -g phpuser -s /bin/bash -m phpuser


USER phpuser


# Открытие порта для PHP-FPM
EXPOSE 9000


# Запуск PHP-FPM
CMD ["php-fpm", "-y", "/usr/local/etc/php-fpm.conf", "-R"]