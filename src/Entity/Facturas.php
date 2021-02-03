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
	 * Many Facturas have Many Productos.
	 * @ORM\ManyToMany(targetEntity="App\Entity\Producto")
	 * @ORM\JoinTable(name="facturas_productos",
	 *     joinColumns={@ORM\JoinColumn(name="factura_id", referencedColumnName="id")},
	 *     inverseJoinColumns={@ORM\JoinColumn(name="productos_id", referencedColumnName="id")})
	 */
	private $productos;

	/**
	 * Many Facturas have Many Servicios.
	 * @ORM\ManyToMany(targetEntity="App\Entity\Servicio")
	 * @ORM\JoinTable(name="facturas_servicios",
	 *     joinColumns={@ORM\JoinColumn(name="factura_id", referencedColumnName="id")},
	 *     inverseJoinColumns={@ORM\JoinColumn(name="servicios_id", referencedColumnName="id")})
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
	 * @ORM\Column(type="integer")
	 */
	private $id_cliente;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $id_user;

	public function __construct()
	{
		$this->productos = new \Doctrine\Common\Collections\ArrayCollection();
		$this->servicios = new \Doctrine\Common\Collections\ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @return Collection|Producto[]
	 */
	public function getProductos(): Collection
	{
		return $this->productos;
	}

	/**
	 * @return Collection|Servicio[]
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

	public function getIdCliente(): ?int
	{
		return $this->id_cliente;
	}

	public function setIdCliente(int $id_cliente): self
	{
		$this->id_cliente = $id_cliente;

		return $this;
	}

	public function getIdUser(): ?int
	{
		return $this->id_user;
	}

	public function setIdUser(int $id_user): self
	{
		$this->id_user = $id_user;

		return $this;
	}

	public function setProductos(?Producto $productos): self
	{
		$this->productos = $productos;

		return $this;
	}

	public function setServicios(?Servicio $servicios): self
	{
		$this->servicios = $servicios;

		return $this;
	}

	public function addProducto(Producto $producto): self
	{
		if (!$this->productos->contains($producto)) {
			$this->productos[] = $producto;
		}

		return $this;
	}

	public function removeProducto(Producto $producto): self
	{
		$this->productos->removeElement($producto);

		return $this;
	}

	public function addServicio(Servicio $servicio): self
	{
		if (!$this->servicios->contains($servicio)) {
			$this->servicios[] = $servicio;
		}

		return $this;
	}

	public function removeServicio(Servicio $servicio): self
	{
		$this->servicios->removeElement($servicio);

		return $this;
	}
}
