<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\tfchancesRepository")
 */
class tfchances
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\Column(type="integer")
     */
    private $tier1;

    /**
     * @ORM\Column(type="integer")
     */
    private $tier2;

    /**
     * @ORM\Column(type="integer")
     */
    private $tier3;

    /**
     * @ORM\Column(type="integer")
     */
    private $tier4;

    /**
     * @ORM\Column(type="integer")
     */
    private $tier5;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getTier1(): ?int
    {
        return $this->tier1;
    }

    public function setTier1(int $tier1): self
    {
        $this->tier1 = $tier1;

        return $this;
    }

    public function getTier2(): ?int
    {
        return $this->tier2;
    }

    public function setTier2(int $tier2): self
    {
        $this->tier2 = $tier2;

        return $this;
    }

    public function getTier3(): ?int
    {
        return $this->tier3;
    }

    public function setTier3(int $tier3): self
    {
        $this->tier3 = $tier3;

        return $this;
    }

    public function getTier4(): ?int
    {
        return $this->tier4;
    }

    public function setTier4(int $tier4): self
    {
        $this->tier4 = $tier4;

        return $this;
    }

    public function getTier5(): ?int
    {
        return $this->tier5;
    }

    public function setTier5(int $tier5): self
    {
        $this->tier5 = $tier5;

        return $this;
    }
}
