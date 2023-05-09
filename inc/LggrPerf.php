<?php
namespace Lggr;

/**
 * @class LggrPerf
 * @brief Performance measurement class.
 *
 * Yet an empty skeleton, has to get more functionality.
 */
class LggrPerf {

    private $tsStart = null; /** timestamp start */

    private $tsEnd = null; /** timestamp end */

    private $tsLen = null; /** calculated seconds */

    private $sQuery = null; /** Stored SQL query to be measured */

    /**
     * Empty constructor
     */
    public function __construct() {
    	  // nothing to do here
    }

    /**
     * Start timer and store query
     * @param String sql query
     */
    public function start($sql) {
        $this->sQuery = $sql;
        $this->tsStart = microtime(true);
    }

    /**
     * Stop timer and store length
     */
    public function stop() {
        $this->tsEnd = microtime(true);
        $this->tsLen = $this->tsEnd - $this->tsStart;
    }

    /**
     * Return array with time and query
     * @return array with keys time and query
     */
    public function getPerf() {
        $a = array();
        
        $a['time'] = $this->tsLen;
        $a['query'] = $this->sQuery;
        
        $this->logperf();
        
        return $a;
    }

    /**
     * write performance info to logging layer, for later
     */
    private function logPerf() {}
}

