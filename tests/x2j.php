<?php

$xfile = file_get_contents(__DIR__ . '/../logs/junit.xml');
$xml = simplexml_load_string($xfile);
$json = json_encode($xml);
echo $json;
