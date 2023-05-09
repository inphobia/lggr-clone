<?php

require_once __DIR__ . '/../vendor/autoload.php';

$iCountServers = 0;
$aPerf = array();
$l = null;
try {
    $config = new \Lggr\AdminConfig();
    
    $state = new \Lggr\LggrState();
    $state->setLocalCall(true);
    
    $l = new \Lggr\Lggr($state, $config);
    
    $iCountServers = $l->updateServers();
    
    $aPerf = $l->getPerf();
}
catch (\Lggr\LggrException $e) {
    die($e->getMessage());
} // try

$pCount = count($aPerf);
$pTime = 0;
foreach ($aPerf as $perf) {
    $aTmp = $perf->getPerf();
    $pTime += $aTmp['time'];
} // foreach

?>
Purging updating <?= $iCountServers ?> servers with <?= $pCount ?> queries
in <?= number_format((float)$pTime, 2, '.', '') ?> seconds.
