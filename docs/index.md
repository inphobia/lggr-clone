# Home - Lggr

## About

Setting up lggr requires some surrounding tools:

* syslog-ng (with syslog-ng-mod-sql extension module)
* stunnel
* mysql (MariaDB 10.1 used here)
* apache
* php 7.x (7.4 used yet)
* composer (for initial setup)
* npm (for including UI javascript)

And there is a difference betweeen setting up the central lggr server and configuring the multiple clients logging to it.

## Server

### MySQL / MariaDB

First create a database *lggr* and run the 1\_db.sql, 2\_auth.sql and 3\_user.sql scripts from the setup folder into it.
It will create one major table, one server table, and four views.

### syslog-ng

Create a file /etc/syslog-ng/conf.d/08lggr.conf containing:

    options {
    keep_hostnames(yes);
    };
    
    source s_net {
    tcp( ip("127.0.0.1") port(514) max-connections(20) log-iw-size(2000) );
    };
    
    destination d_newmysql {
    sql(
    flags(dont-create-tables,explicit-commits)
    session-statements("SET NAMES 'utf8'")
    flush_lines(10)
    flush_timeout(5000)
    local_time_zone("Europe/Berlin")
    type(mysql)
    username("lggrsyslog")
    password("xxx")
    database("lggr")
    host("localhost")
    table("newlogs")
    columns("date", "facility", "level", "host", "program", "pid", "message")
    values("${R_YEAR}-${R_MONTH}-${R_DAY} ${R_HOUR}:${R_MIN}:${R_SEC}", "$FACILITY", "$LEVEL", "$HOST", "$PROGRAM", "$PID", "$MSGONLY")
    indexes()
    );
    };
    
    log {
    source(s_net); source(s_src); filter(f_no_debug); destination(d_newmysql);
    };

With that configuration syslog-ng logs its own local messages and the ones it receives via TCP from the net.
As you can see we don’t just accept any external tcp connection but use some stunnel construct to only allow athenticated clients.
In case of a plain internal network you might skip that step and listen on IP 0.0.0.0 to get direct access.

Debian users might need some additional packages:

    apt-get install syslog-ng-core syslog-ng-mod-sql
    apt-get install libdbi1 libdbd-mysql

Depending on the linux/debian/syslog-ng versions you might have to enable one line within the file /etc/default/syslog-ng:

    SYSLOGNG_OPTS="–no-caps"

### Apache

Just extract the files into your root web folder, i.e. /var/www/lggr and create a virtual host configuration.
You have to adjust the database connection within inc/Config.php to your needs. Use the read only mysql user.

### PHP

You need at least version 7.3, I’m developing using 7.4.

After extracting the sources, go that folder and run

    composer install
    npm install

Which should create the 'vendor' and 'node\_modules' folders.

### stunnel

Creating a tunnel is somewhat more complex.
You have to create a CA infrastructure with keys and certificates, distribute them to the clients and configure it correct.

For detailed information have a look at snippet.wiki.

To give some hints:

Enable the stunnel within /etc/defaults/stunnel and create a configuration file /etc/stunnel/stunnel.conf:

    CAfile = /etc/stunnel/cacert.pem
    CApath = /etc/stunnel/certs/
    
    cert = /etc/stunnel/logserver_cert.pem
    key = /etc/stunnel/logserver_nopwd_key.pem
    pid =  /var/run/stunnel4.pid
    
    verify = 3
    debug = 5
    [5140]
    accept = 10.10.10.10:5140
    connect = 127.0.0.1:514

Where 10.10.10.10 ist your public external IP.
Now the stunnel should listen on port 5140 to external connects and forward the decrypted connection to the local syslog tcp port.
The path to your pid file might differ.
