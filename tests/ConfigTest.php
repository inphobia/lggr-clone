<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../vendor/autoload.php';

final class ConfigTest extends TestCase
{
	public function testCreate()
	{
		$cfg = new \Lggr\Config();
		$this->assertInstanceOf(
			\Lggr\AbstractConfig::class,
			$cfg
		);
	}
	public function testDbUser()
	{
		$cfg = new \Lggr\Config();
		$this->assertEquals(
			'logviewer',
			$cfg->getDbUser()
		);
	}
}

