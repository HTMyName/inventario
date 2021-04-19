<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ClienteRepository::class)
 */
class Cliente
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
	private $name;

	/**
	 * @ORM\Column(type="string", length=8, unique=true)
	 */
	private $tell;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $active;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Logs", mappedBy="id_cliente")
	 */
	private $logs;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Facturas", mappedBy="id_cliente")
	 */
	private $facturas;

	/**
	 * @ORM\Column(type="float")
	 */
	private $descuento;


	public function __construct()
	{
		$this->logs = new ArrayCollection();
		$this->facturas = new ArrayCollection();
	}

	public function getActive(): ?bool
	{
		return $this->active;
	}

	public function setActive(bool $active): self
	{
		$this->active = $active;

		return $this;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function getTell(): ?string
	{
		return $this->tell;
	}

	public function setTell(string $tell): self
	{
		$this->tell = $tell;

		return $this;
	}

	/**
	 * @return Collection|Logs[]
	 */
	public function getLogs(): Collection
	{
		return $this->logs;
	}

	public function setLogs(?Logs $logs): self
	{
		$this->logs = $logs;

		return $this;
	}

	public function addLog(Logs $log): self
	{
		if (!$this->logs->contains($log)) {
			$this->logs[] = $log;
			$log->setIdCliente($this);
		}

		return $this;
	}

	public function removeLog(Logs $log): self
	{
		if ($this->logs->removeElement($log)) {
			// set the owning side to null (unless already changed)
			if ($log->getIdCliente() === $this) {
				$log->setIdCliente(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection|Facturas[]
	 */
	public function getFacturas(): Collection
	{
		return $this->facturas;
	}

	public function addFactura(Facturas $factura): self
	{
		if (!$this->facturas->contains($factura)) {
			$this->facturas[] = $factura;
			$factura->setIdCliente($this);
		}

		return $this;
	}

	public function removeFactura(Facturas $factura): self
	{
		if ($this->facturas->removeElement($factura)) {
			// set the owning side to null (unless already changed)
			if ($factura->getIdCliente() === $this) {
				$factura->setIdCliente(null);
			}
		}

		return $this;
	}

	public function __toString()
	{
		return $this->name;
	}

	public function getDescuento(): ?float
	{
		return $this->descuento;
	}

	public function setDescuento(float $descuento): self
	{
		$this->descuento = $descuento;

		return $this;
	}
}
