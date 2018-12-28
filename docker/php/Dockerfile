FROM php:7.3-fpm-alpine3.8

ARG USER_UID

RUN apk update && apk upgrade && \
    apk --no-cache add \
        acl

RUN docker-php-ext-install pdo pdo_mysql

# COPY docker/php/conf/pdo_mysql.ini /usr/local/etc/php/conf.d/pdo_mysql.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN addgroup -S -g $USER_UID 360-dev && \
    adduser -S -u $USER_UID -G 360-dev 360-dev

RUN chown -R 360-dev:360-dev /var/www/html

USER 360-dev

RUN composer --version

EXPOSE 9000
CMD ["php-fpm"]