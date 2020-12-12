# Docker setup

## Folder structure
Starting with an empty folder we create this structure:

    root@www1 ~/dockers/lggrdemo # ls -la
    drwxr-xr-x  4 root root 4096 Dec 12 11:47 .
    drwxr-xr-x 27 root root 4096 Dec 12 10:21 ..
    drwxr-xr-x  5 root root 4096 Dec 12 10:58 data
    -rw-r--r--  1 root root  598 Dec 12 11:47 docker-compose.yml
    drwxr-xr-x  3 root root 4096 Dec 12 11:46 webphp74


## docker compose file

The main docker-compose.yml will look like this:

    version: '3.7'
    
    services:
      mysql:
        image: mariadb:10.1
        volumes:
          - ./data/mysql/:/var/lib/mysql
          - ./data/initdb:/docker-entrypoint-initdb.d:ro
        restart: always
        networks:
          - lggr
        environment:
          MYSQL_ROOT_PASSWORD: thesecret
          MYSQL_DATABASE: lggr
          MYSQL_USER: lggrci
          MYSQL_PASSWORD: xxx
    
      redis:
        image: redis:latest
        restart: always
        networks:
          - lggr
    
      web:
        depends_on:
          - mysql
          - redis
        build: webphp74/
        ports:
          - "127.0.0.1:888:80"
        restart: always
        networks:
          - lggr
    
    networks:
      lggr:

## data subfolder

The data subfolder will be used by the mysql container.
We create an empty folder mysql for storing the database between restarts of the container.
Within the initdb folder we copy the three setup sql files for initial creation of the db structure.

    root@www1 ~/dockers/lggrdemo/data # ls -la     
    drwxr-xr-x 4 root root 4096 Dec 12 11:54 .     
    drwxr-xr-x 4 root root 4096 Dec 12 11:47 ..    
    drwxr-xr-x 2 root root 4096 Dec 12 10:57 initdb
    drwxr-xr-x 5  999 root 4096 Dec 12 11:51 mysql

    root@www1 ~/dockers/lggrdemo/data/initdb # ls -la  
    drwxr-xr-x 2 root root 4096 Dec 12 10:57 .         
    drwxr-xr-x 4 root root 4096 Dec 12 11:54 ..        
    -rw-r--r-- 1 root root 8803 Dec 12 10:56 1_db.sql  
    -rw-r--r-- 1 root root 4271 Dec 12 10:56 2_auth.sql
    -rw-r--r-- 1 root root 2313 Dec 12 10:56 3_user.sql

## Dockerfile

The webphp74 subfolder will contain the docker file for the web server image and a git clone of the project itself.

The lggr folder will be created by a git clone command. Be sure to enter the folder and switch to the develop branch.

    root@www1 ~/dockers/lggrdemo/webphp74 # ls -la      
    drwxr-xr-x  3 root root 4096 Dec 12 11:46 .         
    drwxr-xr-x  4 root root 4096 Dec 12 11:47 ..        
    -rw-r--r--  1 root root  902 Dec 12 11:46 Dockerfile
    drwxr-xr-x 18 root root 4096 Dec 12 11:46 lggr

The Dockerfile finally has this content:

    FROM php:7.4-apache
    LABEL maintainer="Kai KRETSCHMANN"
    
    RUN apt-get update && apt-get install -y \
            mariadb-client curl wget git zip iproute2
    RUN pecl install xdebug && docker-php-ext-enable xdebug
    RUN docker-php-ext-install mysqli pdo pdo_mysql gettext
    RUN a2enmod expires
    
    RUN wget https://composer.github.io/installer.sig -O - -q | tr -d '\n' > installer.sig \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('SHA384', 'composer-setup.php') === file_get_contents('installer.sig')) { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php && mv composer.phar /usr/bin/composer \
    && php -r "unlink('composer-setup.php'); unlink('installer.sig');"
    
    COPY lggr/ /var/www/html/
    RUN ls -l /var/www/html
    RUN cd /var/www/html && composer --no-interaction install