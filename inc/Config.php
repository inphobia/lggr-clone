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
        $this->setDbHost('localhost');

        
        // Set your preferred language en_US, de_DE, or pt_BR
        $this->setLocale('en_US');
        
        /* remote storage */
        $this->setUrlBootstrap('/vendor/twbs/bootstrap/dist/');
        $this->setUrlJquery('/vendor/components/jquery/');
        $this->setUrlJqueryui('/vendor/components/jqueryui/');
        $this->setUrlJAtimepicker(
            '//cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/');
        $this->setUrlChartjs('//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/');
        $this->setUrlJQCloud('//cdnjs.cloudflare.com/ajax/libs/jqcloud/1.0.4/');
    }
}
