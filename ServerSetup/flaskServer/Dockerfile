FROM python:3

ADD . .

RUN pip3 install --no-cache-dir -r flask/requirements.txt
RUN mv my.cnf /etc/mysql/

CMD cd flask; export FLASK_APP=app.py; flask run --host=0.0.0.0