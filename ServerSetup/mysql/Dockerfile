FROM debian

ADD . .

ENV MYSQL_PWD "root"

RUN echo "mysql-server mysql-server/root_password password $MYSQL_PWD" | debconf-set-selections
RUN echo "mysql-server mysql-server/root_password_again password $MYSQL_PWD" | debconf-set-selections

RUN apt-get update && apt-get install -y mysql-server

RUN /bin/bash -c "/usr/bin/mysqld_safe & " && sleep 5 && mysql -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'php'@'localhost' IDENTIFIED BY 'password';"

####ADD IN MYSQL SCRIPT THAT INITIATES THE DB###########

CMD /bin/bash -c "/usr/bin/mysqld_safe"