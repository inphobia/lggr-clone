<?php

require_once __DIR__ . '/../vendor/autoload.php';

$iCount = 0;
$aPerf = array();
$l = null;
try {
    $config = new \Lggr\AdminConfig();
    $iMaxAge = $config->getMaxAge();
?>
Start purging msgs older than <?= $iMaxAge ?> hours ...
<?php
    
    $state = new \Lggr\LggrState();
    $state->setLocalCall(true);
    
    $l = new \Lggr\Lggr($state, $config);
    
    $iCount = $l->purgeOldMessages($iMaxAge);
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
Purging <?= $iCount ?> old messages with <?= $pCount ?> queries
in <?= number_format((float)$pTime, 2, '.', '') ?> seconds.
