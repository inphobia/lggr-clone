<?php

namespace Lggr;

use PHPUnit\Framework\TestCase;

final class LggrTest extends TestCase
{
	private $state=null;
	private $config=null;

	private function loadConfig()
	{
		switch($GLOBALS['CONFIGCLASS']) {
		case 'LocalConfig':
			echo 'Local Config used';
			return new LocalConfig();
			break;
		 case 'CiConfig':
                        echo 'CI Config used';
                        return new CiConfig();
                        break;
		}
	}

	public function testClassConstructor()
	{
		$this->state = new LggrState();
		$this->config = $this->loadConfig();
		$l = new Lggr($this->state, $this->config);

		$this->assertInstanceOf(Lggr::class, $l);
	}
}

