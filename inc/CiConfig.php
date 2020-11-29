<?php
namespace Lggr;

class CiConfig extends AbstractConfig {

    function __construct() {
        $this->setDbUser('lggrci');
        $this->setDbPwd('xxx');
	$this->setDbName('lggr');
	$this->setDbHost('localhost');
    } // constructor
} // class
