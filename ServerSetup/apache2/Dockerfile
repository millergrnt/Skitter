FROM ubuntu

ADD . .

RUN apt-get update && apt-get install -y apache2
RUN cp public-html/* /var/www/html
RUN mv ports.conf /etc/apache2/
RUN mv 000-default.conf /etc/apache2/sites-enabled/

CMD apachectl -DFOREGROUND
