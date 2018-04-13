FROM ubuntu
ADD . .

RUN apt-get update && apt-get install -y haproxy
RUN mv haproxy.cfg /etc/haproxy/

#CMD haproxy -V haproxy.cfg
CMD /etc/init.d/haproxy start && tail -F /var/log/messages