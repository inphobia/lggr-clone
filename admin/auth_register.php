<?php

require_once '../vendor/autoload.php';

use Lggr\AuthConfig;

use PHPAuth\Config as PHPAuthConfig;
use PHPAuth\Auth as PHPAuth;

use GetOptionKit\OptionCollection;
use GetOptionKit\OptionParser;
use GetOptionKit\OptionPrinter\ConsoleOptionPrinter;

$config = new AuthConfig();

$specs = new OptionCollection;
$specs->add('e|email:', 'email for login')->isa('Email');
$specs->add('p|password:', 'password for login')->isa('String');

$printer = new ConsoleOptionPrinter();
$parser = new OptionParser($specs);

$u_email = null;
$u_password = null;
try
{
	$result = $parser->parse($argv);

	if(!array_key_exists('email', $result->keys)) {
		throw new InvalidArgumentException('email missing');
	}
	if(!array_key_exists('password', $result->keys)) {
		throw new InvalidArgumentException('password missing');
	}

	$u_email = $result->keys['email']->value;
	$u_password = $result->keys['password']->value;
}
catch(Exception $e)
{
	echo $e->getMessage() . "\n\n";
	echo $printer->render($specs);
	exit(1);
}

$dbh = new PDO(
	"mysql:host=" . $config->getDbHost() .
	";dbname=" . $config->getDbName(),
	$config->getDbUser(),
	$config->getDbPwd()
);
$authconfig = new PHPAuthConfig($dbh);
$auth = new PHPAuth($dbh, $authconfig);

$o = $auth->register($u_email, $u_password, $u_password, Array(), null, false);
if($o['error'])
{
	echo $o['message'] . "\n";
	exit(2);
}
else
{
	echo "OK\n";
}
