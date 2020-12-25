<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../vendor/autoload.php';
require 'GenericDbTestCase.php';

final class ServersTest extends GenericDbTestCase
{
	protected static $cfg;
	protected static $state;
	protected static $lggr;

	public static function setUpBeforeClass(): void
	{
		self::$cfg = new \Lggr\Config();
		self::$state = new \Lggr\LggrState();
		self::$lggr = new \Lggr\Lggr(self::$state, self::$cfg);
	}

	public function testCreate(): void
	{
		$this->assertInstanceOf(
			\Lggr\Lggr::class,
			self::$lggr
		);
	}
	public function testServers(): void
	{
		$a = self::$lggr->getAllServers();
		$this->assertGreaterThan(
			1,
			count($a)
		);
	}
}

