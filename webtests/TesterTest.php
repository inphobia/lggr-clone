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

}
