# create the following different mysql users:

USE mysql;

CREATE OR REPLACE USER lggrauth@'%' IDENTIFIED BY 'xxx';
CREATE OR REPLACE USER lggrauth@localhost IDENTIFIED BY 'xxx';
GRANT SELECT ON lggr.phpauth_config TO lggrauth@'%';
GRANT SELECT ON lggr.phpauth_config TO lggrauth@localhost;
GRANT INSERT,SELECT,DELETE,UPDATE ON lggr.phpauth_attempts TO lggrauth@'%';
GRANT INSERT,SELECT,DELETE,UPDATE ON lggr.phpauth_attempts TO lggrauth@localhost;
GRANT INSERT,SELECT,DELETE,UPDATE ON lggr.phpauth_requests TO lggrauth@'%';
GRANT INSERT,SELECT,DELETE,UPDATE ON lggr.phpauth_requests TO lggrauth@localhost;
GRANT INSERT,SELECT,DELETE,UPDATE ON lggr.phpauth_sessions TO lggrauth@'%';
GRANT INSERT,SELECT,DELETE,UPDATE ON lggr.phpauth_sessions TO lggrauth@localhost;
GRANT INSERT,SELECT,DELETE,UPDATE ON lggr.phpauth_emails_banned TO lggrauth@'%';
GRANT INSERT,SELECT,DELETE,UPDATE ON lggr.phpauth_emails_banned TO lggrauth@localhost;
GRANT INSERT,SELECT,DELETE,UPDATE ON lggr.phpauth_users TO lggrauth@'%';
GRANT INSERT,SELECT,DELETE,UPDATE ON lggr.phpauth_users TO lggrauth@localhost;

# used by syslog-ng for inserting new data, referenced in /etc/syslog-ng/conf.d/08newlogsql.conf
CREATE OR REPLACE USER lggrsyslog@'%' IDENTIFIED BY 'xxx';
CREATE OR REPLACE USER lggrsyslog@localhost IDENTIFIED BY 'xxx';
GRANT INSERT ON lggr.newlogs TO lggrsyslog@'%';
GRANT INSERT ON lggr.newlogs TO lggrsyslog@localhost;

# used by the web gui for normal viewing, referenced in inc/config_class.php
CREATE OR REPLACE USER lggrweb@'%' IDENTIFIED BY 'xxx';
CREATE OR REPLACE USER lggrweb@localhost IDENTIFIED BY 'xxx';
GRANT SELECT ON lggr.* TO lggrweb@'%';
GRANT SELECT ON lggr.* TO lggrweb@localhost;
GRANT UPDATE(archived) ON lggr.newlogs TO lggrweb@'%';
GRANT UPDATE(archived) ON lggr.newlogs TO lggrweb@localhost;

# used by clean up cron job and for archiving, referenced in inc/adminconfig_class.php
CREATE OR REPLACE USER lggrcron@'%' IDENTIFIED BY 'xxx';
CREATE OR REPLACE USER lggrcron@localhost IDENTIFIED BY 'xxx';
GRANT EXECUTE ON lggr.* TO lggrcron@'%';
GRANT EXECUTE ON lggr.* TO lggrcron@localhost;
GRANT SELECT,DELETE ON lggr.newlogs TO lggrcron@'%';
GRANT SELECT,DELETE ON lggr.newlogs TO lggrcron@localhost;
GRANT SELECT,INSERT,DELETE  ON TABLE lggr.servers TO lggrcron@'%';
GRANT SELECT,INSERT,DELETE  ON TABLE lggr.servers TO lggrcron@localhost;
GRANT SELECT ON lggr.phpauth_config TO lggrcron@'%';
GRANT SELECT ON lggr.phpauth_config TO lggrcron@localhost;
GRANT SELECT ON lggr.phpauth_users TO lggrcron@'%';
GRANT SELECT ON lggr.phpauth_users TO lggrcron@localhost;

# used for CI, skip for live setup
CREATE OR REPLACE USER lggrci@'%' IDENTIFIED BY 'xxx';
GRANT ALL ON lggr.* TO lggrci@'%';

# activate changes
FLUSH PRIVILEGES;
