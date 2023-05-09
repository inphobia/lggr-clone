<?php
namespace Lggr;

/**
 * @brief Configuration for admin database access.
 */
class AdminConfig extends AbstractConfig {

    public function __construct() {
        $this->setDbUser('lggrcron');
        $this->setDbPwd('xxx');
        $this->setDbName('lggr');
        $this->setDbHost('mysql');
        $this->setMaxAge(672);
    }

}
