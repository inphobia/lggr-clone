FROM debian:stable
MAINTAINER Kai Kretschmann

RUN apt-get update -y
RUN apt-get install -y mariadb-server apache2 php-mysql php-gd
RUN a2enmod rewrite
RUN a2enmod headers
RUN service apache2 restart

EXPOSE 80

CMD ["/bin/ping", "localhost"]

# docker build -t lggr/test .
# docker run -p 4000:80 lggr/test
# docker container rm cea8...
# docker container rm lggr/test
#
