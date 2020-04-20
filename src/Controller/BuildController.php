<?php

namespace App\Controller;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Intervention\Image\ImageManagerStatic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BuildController extends AbstractController
{
    private const HOST = 'docker.for.win.localhost:9515';
    private const URL = 'https://champion.gg/champion/Aatrox/Top';
    private const USERNAME = 'access@ceterus.com';
    private const PASSWORD = 'Porter16!';

    /**
     * @Route("/names", name="names")
     */
    public function getNames()
    {
        $driver = $this->createDriver();
        $driver->get('https://euw.leagueoflegends.com/en-gb/champions/');
        $names = $driver->findElements(WebDriverBy::xpath('//span[@class="style__Text-sc-12h96bu-3 gPUACV"]'));
        foreach ($names as $name) {
            $conn = $this->getDoctrine()->getManager()->getConnection();
            $sql = '
            INSERT INTO Champions (name) VALUES ("' . ucwords(strtolower($name->getText())) . '")';
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
    }

    private function createDriver()
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
        return $driver;
    }

    /**
     * @Route("/info", name="info")
     */
    public function getInfo()
    {
        $champions = $this->getMissingRuneChamp();
        $driver = $this->createDriver();
        foreach ($champions as $champ) {
            $name = $champ["Name"];
            $driver->get('https://champion.gg/champion/' .
                str_replace('.', '', str_replace(' ', '', $name))
            );
            $driver->wait(10);
            $atribute = $driver->findElement(WebDriverBy::xpath("//h2[contains(text(),\"Most Frequent Runes\")]"))->getLocation();
            $driver->executeScript("window.scrollTo(0," . $atribute->getY() . ")");
            $imgPath = 'Rune/' . $name . '.png';
            $driver->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(
                WebDriverBy::xpath(
                    "//h2[contains(text(),\"Most Frequent Runes\")]"
                )

            ));
            $driver->takeScreenshot($imgPath);
            $this->storeRuneByName($name, $imgPath);
        }
        $driver->close();
    }

    private function getAllChampions()
    {
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $sql = 'SELECT * FROM Champions';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    private function getMissingRuneChamp()
    {
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $sql = 'SELECT * FROM Champions WHERE Rune IS NULL';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    private function storeRuneByName($name, $imgPath)
    {
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $sql = 'UPDATE Champions SET Rune = "' . $imgPath . '" WHERE Name = "' . $name . '"';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    /**
     * @Route("/index", name="index")
     */
    public function index()
    {
        $champions = $this->getAllChampions();
        return $this->render('build/index.html.twig', [
            'champions' => $champions,
        ]);
    }

    /**
     * @Route("/runes", name="runes")
     */
    public function getRunes()
    {
        $champions = $this->getAllChampions();
        foreach ($champions as $champion) {
            $source = 'Default/' . $champion["Name"] . '.png';
            $dest = 'Rune/' . $champion["Name"] . '.png';
            $img = ImageManagerStatic::make($source);
            $img->crop(680, 550, 840, 40);
            $img->save($dest);
        }
        dd("done");
    }
    /**
     * @Route("/skills", name="skills")
     */
    public function getSkills()
    {
        $champions = $this->getAllChampions();
        foreach ($champions as $champion) {
            $source = 'Default/' . $champion["Name"] . '.png';
            $dest = 'Skill/' . $champion["Name"] . '.png';
            $img = ImageManagerStatic::make($source);
            $img->crop(700, 220, 130, 10);
            $img->save($dest);
        }
        dd("done");
    }
}
