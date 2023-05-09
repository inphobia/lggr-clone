<?php
namespace Lggr;

require_once __DIR__ . '/../vendor/autoload.php';

$iCount = 0;
$a = array();
$l = null;
try {
    $config = new AdminConfig();
    
    $state = new LggrState();
    $state->setLocalCall(true);
    
    $l = new Lggr($state, $config);
    
    $l->normalizeHosts();
    
    $a = $l->getPerf();
} catch (LggrException $e) {
    die($e->getMessage());
} // try
