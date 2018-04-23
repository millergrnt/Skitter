FROM tomcat:latest

ADD . .

RUN mkdir /usr/local/tomcat/webapps/Skitter/
RUN mv WEB-INF /usr/local/tomcat/webapps/Skitter/
RUN mv index.html /usr/local/tomcat/webapps/Skitter/
RUN mv login.html /usr/local/tomcat/webapps/Skitter/
RUN mv LoginSuccess.jsp /usr/local/tomcat/webapps/Skitter/

RUN cp -r mysql-connector-java-8.0.11 /usr/local/tomcat/lib/
RUN cp mysql-connector-java-8.0.11/mysql-connector-java-8.0.11.jar /usr/local/tomcat/lib/
RUN cp mysql-connector-java-8.0.11/mysql-connector-java-5.1.46.jar /usr/local/tomcat/lib/

RUN apt-get update; apt-get install -y default-jre default-jdk

RUN javac -cp .:/usr/local/tomcat/lib/mysql-connector-java-8.0.11/* \
	/usr/local/tomcat/webapps/Skitter/WEB-INF/classes/LoginServlet.java \
	-d /usr/local/tomcat/webapps/Skitter/WEB-INF/classes/

RUN javac -cp .:/usr/local/tomcat/lib/mysql-connector-java-8.0.11/* \
	/usr/local/tomcat/webapps/Skitter/WEB-INF/classes/LogoutServlet.java \
	-d /usr/local/tomcat/webapps/Skitter/WEB-INF/classes/

RUN javac -cp .:/usr/local/tomcat/lib/mysql-connector-java-8.0.11/* \
	/usr/local/tomcat/webapps/Skitter/WEB-INF/classes/isAuthenticated.java \
	-d /usr/local/tomcat/webapps/Skitter/WEB-INF/classes/

RUN javac -cp .:/usr/local/tomcat/lib/mysql-connector-java-8.0.11/* \
	/usr/local/tomcat/webapps/Skitter/WEB-INF/classes/Register.java \
	-d /usr/local/tomcat/webapps/Skitter/WEB-INF/classes/

CMD /usr/local/tomcat/bin/catalina.sh run