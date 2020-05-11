<?php

namespace App\Controller;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class CustomWebDriver
{
    const HOST = 'docker.for.win.localhost:9515';
    private $driver;

    public function __construct()
    {
        set_time_limit(0);
        $options = new ChromeOptions();
        $options->addArguments([
            '--start-maximized',
        ]);
        $caps = DesiredCapabilities::chrome();
        $caps->setCapability('chrome', $options);
        $driver = RemoteWebDriver::create(self::HOST, $caps,
            80 * 1000,
            80 * 1000
        );
        $driver->manage()->window()->maximize();
        $this->driver = $driver;
    }

    /**
     * @return RemoteWebDriver
     */
    public function getDriver(): RemoteWebDriver
    {
        return $this->driver;
    }

}