<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductoRepository::class)
 */
class Producto
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
	private $marca;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $modelo;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $serie;

	/**
	 * @ORM\Column(type="float")
	 */
	private $precioC;

	/**
	 * @ORM\Column(type="float")
	 */
	private $precioV;

	/**
	 * @ORM\Column(type="float")
	 */
	private $ganancia;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $active;

	/**
	 * @return mixed
	 */
	public function getActive()
	{
		return $this->active;
	}

	/**
	 * @param mixed $active
	 */
	public function setActive($active): void
	{
		$this->active = $active;
	}


	public function getId(): ?int
	{
		return $this->id;
	}

	public function getMarca(): ?string
	{
		return $this->marca;
	}

	public function setMarca(string $marca): self
	{
		$this->marca = $marca;

		return $this;
	}

	public function getModelo(): ?string
	{
		return $this->modelo;
	}

	public function setModelo(string $modelo): self
	{
		$this->modelo = $modelo;

		return $this;
	}

	public function getSerie(): ?string
	{
		return $this->serie;
	}

	public function setSerie(string $serie): self
	{
		$this->serie = $serie;

		return $this;
	}

	public function getPrecioC(): ?float
	{
		return $this->precioC;
	}

	public function setPrecioC(float $precioC): self
	{
		$this->precioC = $precioC;

		return $this;
	}

	public function getPrecioV(): ?float
	{
		return $this->precioV;
	}

	public function setPrecioV(float $precioV): self
	{
		$this->precioV = $precioV;

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
}
