<?php

require_once 'inc/pre.inc.php';
require_once 'inc/auth.inc.php';

$user = $_POST['authEmail'];
$pwd = $_POST['authPassword'];
$o = $auth->login($user, $pwd, true);

if($o['error']) {
	if(isset($_COOKIE[($authConfig->cookie_name])) {
		unset($_COOKIE[($authConfig->cookie_name]);
		setcookie($authConfig->cookie_name, null, -1, $authConfig->cookie_path);
	}
	header('Location: index.php');
} else {
	$_SESSION['authHash'] = $o['hash'];
	setcookie($authConfig->cookie_name, $o['hash'], $o['expire'],
		$authConfig->cookie_path, $authConfig->cookie_domain, $authConfig->cookie_secure, $authConfig->cookie_http);
	header('Location: index.php');
}
