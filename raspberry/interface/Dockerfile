FROM node:9

MAINTAINER Simon Minotto <simon.minotto@wedia.fr>

ENV SRC /usr/src

# Install app dependencies
WORKDIR $SRC

RUN apt-get update && apt-get install nano

COPY package*.json $SRC/
COPY webpack.config.js $SRC
COPY yarn.lock $SRC

RUN yarn
RUN yarn install

EXPOSE 8011
CMD [ "npm", "run", "--unsafe-perm", "start" ]