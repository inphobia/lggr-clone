<?php
namespace Lggr;

/**
 * @brief Configuration class for CI tests.
 */
class CiConfig extends AbstractConfig
{

    public function __construct()
    {
        $this->setDbUser('lggrci');
        $this->setDbPwd('xxx');
        $this->setDbName('lggr');
	$this->setDbHost('mysql');
        $this->setCacheHost('redis');
    }
}
