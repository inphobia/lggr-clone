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

The main *docker-compose.yml* will look like this:

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
    
      syslog:
        image: balabit/syslog-ng
        restart: always
        depends_on:
          - mysql
        cap_add:
          - ALL
        volumes:
          - ./data/syslog/syslog-ng.conf:/etc/syslog-ng/syslog-ng.conf:ro
          - ./data/syslog/lggr.conf:/etc/syslog-ng/conf.d/lggr.conf:ro
        networks:
          - lggr
    
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

The *data* subfolder will be used by the mysql and syslog containers.
We create an empty folder *mysql* for storing the database between restarts of the container.
Within the *initdb* folder we copy the three setup sql files for initial creation of the db structure.

    root@www1 ~/dockers/lggrdemo/data # ls -la     
    drwxr-xr-x 4 root root 4096 Dec 12 11:54 .     
    drwxr-xr-x 4 root root 4096 Dec 12 11:47 ..    
    drwxr-xr-x 2 root root 4096 Dec 12 10:57 initdb
    drwxr-xr-x 5  999 root 4096 Dec 12 11:51 mysql
    drwxr-xr-x 2 root root 4096 Dec 12 16:34 syslog

### MySQL init data

    root@www1 ~/dockers/lggrdemo/data/initdb # ls -la  
    drwxr-xr-x 2 root root 4096 Dec 12 10:57 .         
    drwxr-xr-x 4 root root 4096 Dec 12 11:54 ..        
    -rw-r--r-- 1 root root 8803 Dec 12 10:56 1_db.sql  
    -rw-r--r-- 1 root root 4271 Dec 12 10:56 2_auth.sql
    -rw-r--r-- 1 root root 2313 Dec 12 10:56 3_user.sql

### Syslog-NG init data

Here we place a copy of the original *syslog-ng.conf* enhanced for an include of conf.d files,
and also the *lggr.conf* linked into that *conf.d* folder:

    root@www1 ~/dockers/lggrdemo/data/syslog # ls -la
    drwxr-xr-x 2 root root 4096 Dec 12 16:34 .
    drwxr-xr-x 5 root root 4096 Dec 12 16:18 ..
    -rw-r--r-- 1 root root  637 Dec 12 16:22 lggr.conf
    -rw-r--r-- 1 root root 1513 Dec 12 16:30 syslog-ng.conf

First the original *syslog-ng.conf* copy:

    @version: 3.29
    @include "scl.conf"
    
    source s_local {
            internal();
    };
    
    source s_network {
            default-network-drivers(
                #tls(key-file("/path/to/ssl-private-key") cert-file("/path/to/ssl-cert"))
            );
    };
    
    destination d_local {
            file("/var/log/messages");
            file("/var/log/messages-kv.log" template("$ISODATE $HOST $(format-welf --scope all-nv-pairs)\n") frac-digits(3));
    };
    
    log {
            source(s_local);
            source(s_network);
            destination(d_local);
    };
    
    ###
    # Include all config files in /etc/syslog-ng/conf.d/
    ###
    @include "/etc/syslog-ng/conf.d/*.conf"

And our own *lggr.conf* contents:

    destination d_newmysql {
      sql(
        flags(dont-create-tables,explicit-commits)
        session-statements("SET NAMES 'utf8'")
        batch_lines(10)
        batch_timeout(5000)
        local_time_zone("Europe/Berlin")
        type(mysql)
        username("lggrsyslog")
        password("xxx")
        database("lggr")
        host("mysql")
        table("newlogs")
        columns("date", "facility", "level", "host", "program", "pid", "message")
        values("${R_YEAR}-${R_MONTH}-${R_DAY} ${R_HOUR}:${R_MIN}:${R_SEC}", "$FACILITY", "$LEVEL", "$HOST", "$PROGRAM", "$PID", "$MSGONLY")
        indexes()
      );
    };
    
    log {
        source(s_local); source(s_network); destination(d_newmysql);
    };

## Dockerfile

The *webphp74* subfolder will contain the docker file for the web server image and a git clone of the project itself.

The lggr folder will be created by a git clone command. Be sure to enter the folder and switch to the develop branch.

    root@www1 ~/dockers/lggrdemo/webphp74 # ls -la      
    drwxr-xr-x  3 root root 4096 Dec 12 11:46 .         
    drwxr-xr-x  4 root root 4096 Dec 12 11:47 ..        
    -rw-r--r--  1 root root  902 Dec 12 11:46 Dockerfile
    drwxr-xr-x 18 root root 4096 Dec 12 11:46 lggr

The *Dockerfile* finally has this content:

    FROM php:7.4-apache
    LABEL maintainer="Kai KRETSCHMANN"
    
    RUN apt-get update && apt-get install -y \
            mariadb-client curl wget git zip iproute2
    RUN pecl install xdebug && docker-php-ext-enable xdebug
    RUN pecl install redis && docker-php-ext-enable redis
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

## Running composer containers:

After starting via *docker-compose up -d* it should look like this:

    root@www1 ~/dockers/lggrdemo # docker-compose ps
          Name                     Command                  State                 Ports
    ----------------------------------------------------------------------------------------------
    lggrdemo_mysql_1    docker-entrypoint.sh mysqld      Up             3306/tcp
    lggrdemo_redis_1    docker-entrypoint.sh redis ...   Up             6379/tcp
    lggrdemo_syslog_1   /usr/sbin/syslog-ng -F           Up (healthy)   514/udp, 601/tcp, 6514/tcp
    lggrdemo_web_1      docker-php-entrypoint apac ...   Up             127.0.0.1:888->80/tcp

## Initial access

To get into the web gui of **lggr** you now would have to access http://127.0.0.1:888/login.php via a system local browser,
or you forward to that local ip via any reverse proxy.

For login you first have to create a web access user into the database.
For this you can use the command line:

    docker exec -it lggrdemo_web_1 /bin/bash
    # cd admin
    # php auth_register.php --email=info@example.com --password=xxx