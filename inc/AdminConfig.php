<?php
namespace Lggr;

class AdminConfig extends AbstractConfig {

    function __construct() {
        $this->setDbUser('lggrcron');
        $this->setDbPwd('xxx');
	$this->setDbName('lggr');
	$this->setDbHost('localhost');
    } // constructor
} // class
