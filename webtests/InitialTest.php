<?php

use PHPUnit\Framework\TestCase;

require __DIR__ . '/../vendor/autoload.php';

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;

final class InitialTest extends TestCase {
    protected static $cfg;
    protected $webDriver;

    private function buildChromeCapabilities()
    {
        $capabilities = DesiredCapabilities::chrome();
        return $capabilities;
    }

    public static function setUpBeforeClass(): void
    {
        self::$cfg = new \Lggr\Config(); 
    }

    public function setUp(): void
    {
        $capabilities = $this->buildChromeCapabilities();
        $this->webDriver = RemoteWebDriver::create('http://chrome:4444/wd/hub', $capabilities);
    }

    public function tearDown(): void
    {
        $this->webDriver->quit();
    }

    public function testHome(): void
    {
        $this->webDriver->get("http://localhost/");
        sleep(5);
        echo $this->webDriver->getTitle();
    }

}
