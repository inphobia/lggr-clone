FROM debian:stable
MAINTAINER Kai Kretschmann

RUN apt-get update && apt-get install -y \
	mariadb-server \
	apache2 \
	php-mysql php-gd
RUN a2enmod rewrite && a2enmod headers && service apache2 restart

EXPOSE 80

WORKDIR "/var/www/html"
CMD ["/bin/bash"]

# docker build -t lggr/test .
# docker run -p 4000:80 lggr/test
# docker container rm cea8...
# docker container rm lggr/test
#
