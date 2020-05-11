<?php

namespace App\Controller;

use App\Entity\tfchampions;
use App\Entity\tfchances;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\CustomWebDriver;

class TeamFightTacticController extends AbstractController
{
    private $driver;
//    private $customWebDriver;
//
//    public function __construct(CustomWebDriver $customWebDriver)
//    {
//        ;
//        $this->customWebDriver = $customWebDriver;
//    }

    /**
     * @Route("/tf/champions", name="tf/champions")
     */
    public function getChampions()
    {
        $driver = $this->getDriver();
        $driver->get('https://tftactics.gg/db/champions');
        $rows = $driver->findElements(WebDriverBy::xpath('//div[@class="rt-tr-group"]'));
        $champions = [];
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($rows as $row) {
            $champions[] = explode(PHP_EOL, $row->getText());
        }
        foreach ($champions as $championData) {
            if (sizeof($championData) >= 4) {
                $tfChampion = new tfchampions();
                $tfChampion->setName($championData[0]);
                $tfChampion->setOrigin($championData[1]);
                $tfChampion->setClass($championData[2]);
                $tfChampion->setCost((int)$championData[3]);
                $entityManager->persist($tfChampion);
            }
        }
        $entityManager->flush();
        dd("done");
    }

    /**
     * @Route("/tf/chances", name="tf/chances")
     */
    public function getChances()
    {
        $driver = $this->getDriver();
        $driver->get('https://tftactics.gg/db/rolling');
        $rows = $driver->findElements(WebDriverBy::xpath('//div[@class="rt-tr-group"]'));
        $chances = [];
        $entityManager = $this->getDoctrine()->getManager();
        foreach ($rows as $row) {
            $chances[] = explode(PHP_EOL, $row->getText());
        }
        foreach ($chances as $chance) {
            $tfChance = new tfchances();
            $tfChance->setLevel((int)$chance[0]);
            $tfChance->setTier1((int)$chance[1]);
            $tfChance->setTier2((int)$chance[2]);
            $tfChance->setTier3((int)$chance[3]);
            $tfChance->setTier4((int)$chance[4]);
            $tfChance->setTier5((int)$chance[5]);
            $entityManager->persist($tfChance);
        }
        $entityManager->flush();
        dd("done");
    }

    /**
     * @Route("/tf/spin/{level}", name="/tf/spin/{level}")
     * @param $level
     */
    public function spin($level)
    {
        $repository = $this->getDoctrine()->getRepository(tfchances::class);
        /** @var tfchances $chance */
        $chance = $repository->findOneBy(["level" => $level]);
//        dd($chance);
        $result = [];
        for ($i = 0; $i < 5; $i++) {
            $roll = rand(1, 100);
            if ($roll <= $chance->getTier1()){
                $result[] = "1";
            }
            if ($roll > $chance->getTier1() && $roll <= $chance->getTier2()){
                $result[] = "2";
            }
            if ($roll > $chance->getTier2() && $roll <= $chance->getTier3()){
                $result[] = "3";
            }
            if ($roll > $chance->getTier3() && $roll <= $chance->getTier4()){
                $result[] = "4";
            }
            if ($roll > $chance->getTier1() && $roll <= $chance->getTier2()){
                $result[] = "5";
            }
        }
        dd($result);

    }

    /**
     * @return RemoteWebDriver
     */
    public function getDriver(): RemoteWebDriver
    {
        $customWebDriver = new CustomWebDriver();
        $this->driver = $customWebDriver->getDriver();
        return $this->driver;
    }
}
