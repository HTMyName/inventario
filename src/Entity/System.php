<?php

namespace App\Entity;

use App\Repository\SystemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SystemRepository::class)
 */
class System
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pagename;

    /**
     * @ORM\Column(type="float")
     */
    private $winservice;

    /**
     * @ORM\Column(type="float")
     */
    private $winproduct;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPagename(): ?string
    {
        return $this->pagename;
    }

    public function setPagename(string $pagename): self
    {
        $this->pagename = $pagename;

        return $this;
    }

    public function getWinservice(): ?float
    {
        return $this->winservice;
    }

    public function setWinservice(float $winservice): self
    {
        $this->winservice = $winservice;

        return $this;
    }

    public function getWinproduct(): ?float
    {
        return $this->winproduct;
    }

    public function setWinproduct(float $winproduct): self
    {
        $this->winproduct = $winproduct;

        return $this;
    }
}
