<?php

require_once 'inc/pre.inc.php';
require_once 'inc/auth.inc.php';

$o = $auth->logout($auth->getCurrentSessionHash());

header('Location: login.php');
