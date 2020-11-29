<?php

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

$dbh = new PDO("mysql:host=" . $config->getDbHost() . ";dbname=" . $config->getDbName(), $config->getDbUser(), $config->getDbPwd());

$authConfig = new PHPAuthConfig($dbh);
$auth = new PHPAuth($dbh, $authConfig);
