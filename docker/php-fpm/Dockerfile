FROM php:fpm-alpine

COPY wait-for-it.sh /usr/bin/wait-for-it

RUN chmod +x /usr/bin/wait-for-it

RUN apk --update --no-cache add bash wget git && rm -rf /var/cache/apk/*

RUN docker-php-ext-install pdo_mysql opcache

ARG XDEBUG_VERSION=2.9.4
RUN set -eux; \
	apk add --no-cache --virtual .build-deps $PHPIZE_DEPS; \
	pecl install xdebug-$XDEBUG_VERSION; \
	docker-php-ext-enable xdebug opcache; \
	apk del .build-deps

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY  php /usr/local/etc/php/conf.d/

# Symfony tool
RUN wget https://get.symfony.com/cli/installer -O - | bash && \
  mv /root/.symfony/bin/symfony /usr/local/bin/symfony

WORKDIR /var/www

CMD composer install ; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate ;  php-fpm 

EXPOSE 9000
