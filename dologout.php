<?php

require 'inc/pre.inc.php';
require 'inc/auth.inc.php';

$o = $auth->logout($auth->getCurrentSessionHash());

header('Location: login.php');
