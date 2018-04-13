FROM python:3

ADD . .

RUN pip3 install --no-cache-dir -r flask/requirements.txt

CMD cd flask; export FLASK_APP=app.py; flask run
