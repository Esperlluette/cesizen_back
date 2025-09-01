FROM php:8.3-zts-alpine
WORKDIR /app

COPY . . 
RUN apk update && apk upgrade -y 
RUN apk dd wget git -y 
RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/f3108f64b4e1c1ce6eb462b159956461592b3e3e/web/installer -O - -q | php -- --quiet
RUN mv composer.phar /usr/bin/composer
RUN wget https://get.symfony.com/cli/installer -O - | sh 

RUN wget https://get.symfony.com/cli/installer -O - | sh && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony && \
    chmod a+x /usr/local/bin/symfony

RUN composer install


EXPOSE 8080


CMD [ "symfony", "server:start", "--port", "8080"]
