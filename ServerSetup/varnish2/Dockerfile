FROM ubuntu

ADD . .

#Update and Install Varnish
RUN apt-get update && apt install -y varnish

#Set up Varnish configuration
RUN mv apache.vcl /etc/varnish/
RUN mv varnish /etc/default/varnish

#Start Varnish and keep the container running
#CMD /etc/init.d/varnish start && tail -F /var/log/messages
CMD tail -F /etc/varnish/apache.vcl