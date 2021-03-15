<?php

namespace App\Entity;

use App\Repository\FacturasProductoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturasProductoRepository::class)
 */
class FacturasProducto
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Facturas", inversedBy="productos")
	 * @ORM\JoinColumn(name="id_factura", referencedColumnName="id")
	 */
    private $id_factura;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Producto", inversedBy="facturas")
	 * @ORM\JoinColumn(name="id_producto", referencedColumnName="id")
	 */
    private $id_producto;

    /**
     * @ORM\Column(type="integer")
     */
    private $cantidad;

    /**
     * @ORM\Column(type="float")
     */
    private $precio;

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

    public function getIdProducto(): ?Producto
    {
        return $this->id_producto;
    }

    public function setIdProducto(?Producto $id_producto): self
    {
        $this->id_producto = $id_producto;

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

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): self
    {
        $this->precio = $precio;

        return $this;
    }
}
