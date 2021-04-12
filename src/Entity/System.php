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

	/**
	 * @ORM\Column(type="float")
	 */
	private $inversion;

	/**
	 * @ORM\Column(type="float")
	 */
	private $recuperado;

	/**
	 * @ORM\Column(type="float")
	 */
	private $ganancia;

	/**
	 * @ORM\Column(type="float")
	 */
	private $gastos;

	/**
	 * @ORM\Column(type="float")
	 */
	private $caja;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $year_start;

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

	public function getInversion(): ?float
            	{
            		return $this->inversion;
            	}

	public function setInversion(float $inversion): self
            	{
            		$this->inversion = $inversion;
            
            		return $this;
            	}

	public function getRecuperado(): ?float
            	{
            		return $this->recuperado;
            	}

	public function setRecuperado(float $recuperado): self
            	{
            		$this->recuperado = $recuperado;
            
            		return $this;
            	}

	public function getGanancia(): ?float
            	{
            		return $this->ganancia;
            	}

	public function setGanancia(float $ganancia): self
            	{
            		$this->ganancia = $ganancia;
            
            		return $this;
            	}

	public function getCaja(): ?float
            	{
            		return $this->caja;
            	}

	public function setCaja(float $caja): self
            	{
            		$this->caja = $caja;
            
            		return $this;
            	}

    public function getYearStart(): ?int
    {
        return $this->year_start;
    }

    public function setYearStart(int $year_start): self
    {
        $this->year_start = $year_start;

        return $this;
    }

    public function getGastos(): ?float
    {
        return $this->gastos;
    }

    public function setGastos(float $gastos): self
    {
        $this->gastos = $gastos;

        return $this;
    }
}
