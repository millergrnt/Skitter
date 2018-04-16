FROM docker.elastic.co/elasticsearch/elasticsearch:6.2.3

ADD . .

RUN cp elasticsearch.yml /usr/share/elasticsearch/config/
#RUN groupadd -g 999 elastic && \
#	useradd -r -u 999 -g elastic elastic

#USER elastic

CMD /usr/share/elasticsearch/bin/elasticsearch
