FROM httpd

ADD . .

RUN mv index.html /var/www/; ls /var/www

CMD apachectl -DFOREGROUND
