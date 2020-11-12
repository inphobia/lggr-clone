<?php

$xfile = file_get_contents(__DIR__ . '/../logs/junit.xml');
$xml = simplexml_load_string($xfile);
$xml->testsuite->addAttribute('timestamp', date(DATE_ISO8601));
print_r($xml); exit;
$json = json_encode($xml);
echo $json;
