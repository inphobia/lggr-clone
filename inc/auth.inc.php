<?php

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

use Lggr\AuthConfig;

$lggrAuthConfig = new AuthConfig();

$dbh = new PDO("mysql:host=" . $lggrAuthConfig->getDbHost() . ";dbname=" . $lggrAuthConfig->getDbName(), $lggrAuthConfig->getDbUser(), $lggrAuthConfig->getDbPwd());

$authConfig = new PHPAuthConfig($dbh);
$auth = new PHPAuth($dbh, $authConfig);
