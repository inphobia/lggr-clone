<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../vendor/autoload.php';

final class TesterTest extends TestCase {
    protected static $cfg;

    public static function setUpBeforeClass(): void
    {
        self::$cfg = new \Lggr\Config(); 
    }

    public function testIncomplete(): void
    {
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    public function testSkipped(): void
    {
        $this->markTestSkipped(
          'This test has been skipped.'
        );
    }

    // check error handling, skipped because of prefix
    public function tstFailure(): void
    {
    	$this->assertEquals('abc', '123');
    }
}