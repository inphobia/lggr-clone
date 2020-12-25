<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../vendor/autoload.php';

final class AdminConfigTest extends TestCase
{
	protected static $cfg;

	public static function setUpBeforeClass(): void
	{
		self::$cfg = new \Lggr\AdminConfig();
	}

	public function testCreate(): void
	{
		$this->assertInstanceOf(
			\Lggr\AbstractConfig::class,
			self::$cfg
		);
	}
	public function testDbUser(): void
	{
		$this->assertEquals(
			'lggrcron',
			self::$cfg->getDbUser()
		);
	}
}

