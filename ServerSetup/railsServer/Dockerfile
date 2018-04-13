FROM ruby:2.5

RUN apt-get update

COPY ruby/app/Gemfile* ./
RUN bundle install

COPY . .
WORKDIR ruby/app/

CMD ["rails", "server"]
