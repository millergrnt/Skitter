FROM php:7.2-apache

ADD . .

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libmemcached-dev \
    curl \
    zlib1g-dev \
    memcached

RUN curl -L -o /tmp/memcached.tar.gz "https://github.com/php-memcached-dev/php-memcached/archive/v3.0.4.tar.gz" \
    && mkdir -p /usr/src/php/ext/memcached \
    && tar -C /usr/src/php/ext/memcached -zxvf /tmp/memcached.tar.gz --strip 1 \
    && docker-php-ext-configure memcached \
    && docker-php-ext-install memcached \
	&& rm /tmp/memcached.tar.gz

RUN mv php.ini /usr/local/etc/php/conf.d/
RUN mv docker-php-ext-memcached.ini /usr/local/etc/php/conf.d/
RUN mv memcached.conf /etc/memcached.conf
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN cp -r public-html/* /var/www/html/
RUN mv ports.conf /etc/apache2/
RUN mv 000-default.conf /etc/apache2/sites-enabled/

CMD /bin/bash -c "/etc/init.d/memcached start & " && apachectl -DFOREGROUND
