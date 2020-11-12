<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../vendor/autoload.php';

final class TesterTest extends TestCase {
    protected static $cfg;

    public static function setUpBeforeClass() 	{
        self::$cfg = new \Lggr\Config(); 
    }

    // check error handling
    public function testFailure() 	{
    	$this->assertEquals('abc', '123');
    }
}