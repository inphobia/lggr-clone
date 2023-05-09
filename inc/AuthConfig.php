<?php
namespace Lggr;

/*
 * @brief Config class for authentication DB access.
 */
class AuthConfig extends AbstractConfig
{

    public function __construct()
    {
        $this->setDbUser('lggrauth');
        $this->setDbPwd('xxx');
        $this->setDbName('lggr');
        $this->setDbHost('mysql');
    }
}
