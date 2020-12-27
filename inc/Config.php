<?php
namespace Lggr;

/**
 * @brief Default configuration class for web UI.
 */
class Config extends AbstractConfig {

    function __construct() {
        $this->setDbUser('lggrweb');
        $this->setDbPwd('xxx');
        $this->setDbName('lggr');
        $this->setDbHost('mysql');

        
        // Set your preferred language en_US, de_DE, or pt_BR
        $this->setLocale('en_US');
        
        /* remote storage */
        $this->setUrlBootstrap('/vendor/twbs/bootstrap/dist/');
        $this->setUrlJquery('/node_modules/jquery/dist/');
        $this->setUrlJqueryui('/node_modules/jquery-ui-dist/');
        $this->setUrlJAtimepicker('/node_modules/jquery-ui-timepicker-addon/dist/');
        $this->setUrlChartjs('/node_modules/chart.js/dist/');
        $this->setUrlJQCloud('/node_modules/jqcloud-npm/dist/');
    }
}
