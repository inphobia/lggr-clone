<?php

require 'inc/pre.inc.php';
require 'inc/auth.inc.php';

$user = $_POST['authEmail'];
$pwd = $_POST['authPassword'];
$o = $auth->login($user, $pwd, true);

if($o['error']) {
	// TODO
} else {
	$_SESSION['authHash'] = $o['hash'];
	setcookie($authConfig->cookie_name, $o['hash'], $o['expire'], $authConfig->cookie_path, $authConfig->cookie_domain, $authConfig->cookie_secure, $authConfig->cookie_http);
	header('Location: index.php');
}
