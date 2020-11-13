FROM php:7.1-apache
MAINTAINER Kai Kretschmann

RUN apt-get update && apt-get install -y \
	mariadb-client curl wget git zip
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN docker-php-ext-install mysqli

RUN wget https://composer.github.io/installer.sig -O - -q | tr -d '\n' > installer.sig \
&& php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
&& php -r "if (hash_file('SHA384', 'composer-setup.php') === file_get_contents('installer.sig')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
&& php composer-setup.php \
&& php -r "unlink('composer-setup.php'); unlink('installer.sig');"



# docker build -t lggr/test .
# docker run -p 4000:80 lggr/test
# docker container rm cea8...
# docker container rm lggr/test
#
