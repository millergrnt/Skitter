FROM ubuntu

ADD . .

RUN apt-get update && apt-get install -y mysql-client

CMD mysql -h serversetup_mysql_1 -u root -proot -e "source skitter_db.sql"