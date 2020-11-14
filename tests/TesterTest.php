<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../vendor/autoload.php';

final class TesterTest extends TestCase {
    protected static $cfg;

    public static function setUpBeforeClass() 	{
        self::$cfg = new \Lggr\Config(); 
    }

    public function testIncomplete() 	{
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    public function testSkipped() 	{
        $this->markTestSkipped(
          'This test has been skipped.'
        );
    }

    // check error handling
    public function testFailure() 	{
    	$this->assertEquals('abc', '123');
    }
}