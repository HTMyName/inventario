<?php

namespace App\Entity;

use App\Repository\FacturasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturasRepository::class)
 */
class Facturas
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\FacturasProducto", mappedBy="id_factura")
	 */
	private $productos;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\FacturasServicio", mappedBy="id_factura")
	 */
	private $servicios;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $fecha;

	/**
	 * @ORM\Column(type="float")
	 */
	private $total;

	/**
	 * @ORM\Column(type="float")
	 */
	private $total_real;

	/**
	 * @ORM\Column(type="float")
	 */
	private $descuento;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="facturas")
	 * @ORM\JoinColumn(name="id_cliente", referencedColumnName="id")
	 */
	private $id_cliente;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="facturas")
	 * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
	 */
	private $id_user;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Logs", mappedBy="id_factura")
	 */
	private $logs;

	/**
	 * @ORM\Column(type="float")
	 */
	private $xpagar;

	/**
	 * @ORM\Column(type="float")
	 */
	private $saldoRetenidoP;

	/**
	 * @ORM\Column(type="float")
	 */
	private $saldoRetenidoS;

	/**
	 * @ORM\Column(type="float")
	 */
	private $saldoRetenidoI;

	/**
	 * @ORM\Column(type="float")
	 */
	private $saldoRetenidoFI;

	/**
	 * @ORM\Column(type="float")
	 */
	private $saldoRetenidoFG;


	public function __construct()
	{
		$this->productos = new \Doctrine\Common\Collections\ArrayCollection();
		$this->servicios = new \Doctrine\Common\Collections\ArrayCollection();
		$this->logs = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return Collection|FacturasProducto[]
	 */
	public function getProductos(): Collection
	{
		return $this->productos;
	}

	/**
	 * @return Collection|FacturasServicio[]
	 */
	public function getServicios(): Collection
	{
		return $this->servicios;
	}

	public function getFecha(): ?\DateTimeInterface
	{
		return $this->fecha;
	}

	public function setFecha(\DateTimeInterface $fecha): self
	{
		$this->fecha = $fecha;

		return $this;
	}

	public function getTotal(): ?float
	{
		return $this->total;
	}

	public function setTotal(float $total): self
	{
		$this->total = $total;

		return $this;
	}

	public function getIdCliente(): ?Cliente
	{
		return $this->id_cliente;
	}

	public function setIdCliente(?Cliente $id_cliente): self
	{
		$this->id_cliente = $id_cliente;

		return $this;
	}

	public function getIdUser(): ?User
	{
		return $this->id_user;
	}

	public function setIdUser(?User $id_user): self
	{
		$this->id_user = $id_user;

		return $this;
	}

	public function setProductos(string $productos): self
	{
		$this->productos = $productos;

		return $this;
	}

	public function setServicios(string $servicios): self
	{
		$this->servicios = $servicios;

		return $this;
	}

	public function addProducto(FacturasProducto $producto): self
	{
		if (!$this->productos->contains($producto)) {
			$this->productos[] = $producto;
			$producto->setIdFactura($this);
		}

		return $this;
	}

	public function removeProducto(FacturasProducto $producto): self
	{
		if ($this->productos->removeElement($producto)) {
			// set the owning side to null (unless already changed)
			if ($producto->getIdFactura() === $this) {
				$producto->setIdFactura(null);
			}
		}

		return $this;
	}

	public function addServicio(FacturasServicio $servicio): self
	{
		if (!$this->servicios->contains($servicio)) {
			$this->servicios[] = $servicio;
			$servicio->setIdFactura($this);
		}

		return $this;
	}

	public function removeServicio(FacturasServicio $servicio): self
	{
		if ($this->servicios->removeElement($servicio)) {
			// set the owning side to null (unless already changed)
			if ($servicio->getIdFactura() === $this) {
				$servicio->setIdFactura(null);
			}
		}

		return $this;
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

	public function getXpagar(): ?float
	{
		return $this->xpagar;
	}

	public function setXpagar(float $xpagar): self
	{
		$this->xpagar = $xpagar;

		return $this;
	}

	/**
	 * @return Collection|Logs[]
	 */
	public function getLogs(): Collection
	{
		return $this->logs;
	}

	public function addLog(Logs $log): self
	{
		if (!$this->logs->contains($log)) {
			$this->logs[] = $log;
			$log->setIdFactura($this);
		}

		return $this;
	}

	public function removeLog(Logs $log): self
	{
		if ($this->logs->removeElement($log)) {
			// set the owning side to null (unless already changed)
			if ($log->getIdFactura() === $this) {
				$log->setIdFactura(null);
			}
		}

		return $this;
	}

	public function getSaldoRetenidoP(): ?float
	{
		return $this->saldoRetenidoP;
	}

	public function setSaldoRetenidoP(float $saldoRetenidoP): self
	{
		$this->saldoRetenidoP = $saldoRetenidoP;

		return $this;
	}

	public function getSaldoRetenidoS(): ?float
	{
		return $this->saldoRetenidoS;
	}

	public function setSaldoRetenidoS(float $saldoRetenidoS): self
	{
		$this->saldoRetenidoS = $saldoRetenidoS;

		return $this;
	}

	public function getSaldoRetenidoI(): ?float
	{
		return $this->saldoRetenidoI;
	}

	public function setSaldoRetenidoI(float $saldoRetenidoI): self
	{
		$this->saldoRetenidoI = $saldoRetenidoI;

		return $this;
	}

	public function getTotalReal(): ?float
	{
		return $this->total_real;
	}

	public function setTotalReal(float $total_real): self
	{
		$this->total_real = $total_real;

		return $this;
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

	public function getSaldoRetenidoFI(): ?float
	{
		return $this->saldoRetenidoFI;
	}

	public function setSaldoRetenidoFI(float $saldoRetenidoFI): self
	{
		$this->saldoRetenidoFI = $saldoRetenidoFI;

		return $this;
	}

	public function getSaldoRetenidoFG(): ?float
	{
		return $this->saldoRetenidoFG;
	}

	public function setSaldoRetenidoFG(float $saldoRetenidoFG): self
	{
		$this->saldoRetenidoFG = $saldoRetenidoFG;

		return $this;
	}

}
