<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../vendor/autoload.php';

final class AdminConfigTest extends TestCase
{
	protected static $cfg;

	public static function setUpBeforeClass()
	{
		self::$cfg = new \Lggr\AdminConfig();
	}

	public function testCreate()
	{
		$this->assertInstanceOf(
			\Lggr\AbstractConfig::class,
			self::$cfg
		);
	}
	public function testDbUser()
	{
		$this->assertEquals(
			'loggeradmin',
			self::$cfg->getDbUser()
		);
	}
}

