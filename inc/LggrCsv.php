<?php
namespace Lggr;

/**
 * @brief Export as CSV class.
 */
class LggrCsv {

    private $lggr = null;

    const PROPERTIES = [
        'id',
        'date',
        'facility',
        'level',
        'host',
        'program',
        'pid',
        'message'
    ];

    public function __construct(Lggr &$oLggr) {
        $this->lggr = & $oLggr;
    }

    // constructor
    private function generiereDateiname() {
        return 'lggrarchive_' . date('Ymd') . '.csv';
    }

    public function generiere() {
        header('Content-Type: text/csv; charset=utf-8');
        header(
            'Content-Disposition: attachment; filename="' .
                 $this->generiereDateiname() . '"');
        
        $iCnt = 0;
        $aEntries = $this->lggr->getArchived(0, 9999);
        
        foreach ($aEntries as $entry) {
            if (0 == $iCnt) {
                // Spaltentitel
                foreach (self::PROPERTIES as $sProp) {
                    echo $sProp . ";";
                } // foreach
                echo "\n";
            } // if 0
              
            // Wertespalten
            foreach (self::PROPERTIES as $sProp) {
                $sValue = $entry->$sProp;
                
                switch ($sProp) {
                    case 'id':
                        echo "$sValue;";
                        break;
                    
                    case 'message':
                        echo '"' . strtr(utf8_decode($sValue), '"', "'") . "\";";
                        break;
                    
                    default:
                        echo '"' . utf8_decode($sValue) . "\";";
                        break;
                } // switch
            } // foreach
            echo "\n";
            
            $iCnt ++;
        } // foreach
    } // function
} // class
