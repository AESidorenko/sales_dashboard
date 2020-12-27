FROM php:7.4-alpine

RUN docker-php-ext-install mysqli

COPY . /code
WORKDIR /code

ADD https://github.com/ufoscout/docker-compose-wait/releases/download/2.7.3/wait /wait
RUN chmod +x /wait
RUN chmod +x /code/bin/build.sh

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

CMD composer install && /wait && sh /code/bin/build.sh && php -S 0.0.0.0:80 -t /code/public

