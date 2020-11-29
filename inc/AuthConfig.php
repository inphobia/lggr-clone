<?php
namespace Lggr;

class AuthConfig extends AbstractConfig {

    function __construct() {
        $this->setDbUser('lggrauth');
        $this->setDbPwd('xxx');
	$this->setDbName('lggr');
	$this->setDbHost('localhost');
    } // constructor
} // class
