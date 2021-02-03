<?php

namespace App\Entity;

use App\Repository\CuentaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CuentaRepository::class)
 */
class Cuenta
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $inversion;

    /**
     * @ORM\Column(type="float")
     */
    private $fondo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInversion(): ?float
    {
        return $this->inversion;
    }

    public function setInversion(float $inversion): self
    {
        $this->inversion = $inversion;

        return $this;
    }

    public function getFondo(): ?float
    {
        return $this->fondo;
    }

    public function setFondo(float $fondo): self
    {
        $this->fondo = $fondo;

        return $this;
    }
}
