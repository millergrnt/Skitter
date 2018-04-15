FROM ubuntu

ADD . .

RUN apt-get update && apt-get install -y mysql-client

CMD mysql -h 172.18.0.2 -u root -proot -e "source skitter_db.sql"