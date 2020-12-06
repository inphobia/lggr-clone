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
    protected static $logger;
    protected $webDriver;
    protected $webIP;
    protected $driverUrl;

    private function buildChromeCapabilities()
    {
        $capabilities = DesiredCapabilities::chrome();
        return $capabilities;
    }

    public static function setUpBeforeClass(): void
    {
        self::$cfg = new \Lggr\Config(); 
        self::$logger = Logger::getLogger("tests");
    }

    public function setUp(): void
    {
        $capabilities = $this->buildChromeCapabilities();
        $this->driverUrl = getenv('DRIVERURL') ? getenv('DRIVERURL') : 'http://chrome:4444/wd/hub';
	self::$logger->info("Use driver url " . $this->driverUrl);

	$this->webDriver = RemoteWebDriver::create($this->driverUrl, $capabilities);
	$this->webIP = getenv('WEBIP');
	self::$logger->info("Use webserver IP " . $this->webIP);
    }

    public function tearDown(): void
    {
        $this->webDriver->quit();
    }

    public function testHome(): void
    {
        $this->webDriver->get("http://" . $this->webIP . "/");
        sleep(5);
        echo $this->webDriver->getTitle();
    }

}
