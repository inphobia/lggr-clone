<?php
namespace Lggr;

/**
 * @brief Configuration class for CI tests.
 */
class CiConfig extends AbstractConfig {

    function __construct() {
        $this->setDbUser('lggrci');
        $this->setDbPwd('xxx');
        $this->setDbName('lggr');
        $this->setDbHost('localhost');
    }
}
