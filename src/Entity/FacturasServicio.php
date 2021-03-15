<?php

namespace App\Entity;

use App\Repository\FacturasServicioRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturasServicioRepository::class)
 */
class FacturasServicio
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Facturas", inversedBy="servicios")
	 * @ORM\JoinColumn(name="id_factura", referencedColumnName="id")
	 */
	private $id_factura;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Servicio", inversedBy="facturas")
	 * @ORM\JoinColumn(name="id_servicio", referencedColumnName="id")
	 */
	private $id_servicio;

	/**
	 * @ORM\Column(type="float")
	 */
	private $precio;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $cantidad;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdFactura(): ?Facturas
    {
        return $this->id_factura;
    }

    public function setIdFactura(?Facturas $id_factura): self
    {
        $this->id_factura = $id_factura;

        return $this;
    }

    public function getIdServicio(): ?Servicio
    {
        return $this->id_servicio;
    }

    public function setIdServicio(?Servicio $id_servicio): self
    {
        $this->id_servicio = $id_servicio;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): self
    {
        $this->cantidad = $cantidad;

        return $this;
    }
}
