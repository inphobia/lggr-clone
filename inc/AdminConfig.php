<?php
namespace Lggr;

/**
 * @brief Configuration for admin database access.
 */
class AdminConfig extends AbstractConfig {

    function __construct() {
        $this->setDbUser('lggrcron');
        $this->setDbPwd('xxx');
        $this->setDbName('lggr');
        $this->setDbHost('localhost');
    }

}
