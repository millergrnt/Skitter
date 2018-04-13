FROM node

ADD . .

RUN mkdir -p /usr/src/app
RUN cp -r node/* /usr/src/app

WORKDIR /usr/src/app

RUN npm install

CMD npm run dev && tail -F skitBackend.js
