FROM node

ADD . .

RUN mkdir -p /usr/src/app
RUN cp -r node/* /usr/src/app

WORKDIR /usr/src/app

RUN npm install
EXPOSE 61234

CMD npm run dev