<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
	 * @ORM\OneToMany(targetEntity="App\Entity\FacturasProducto", mappedBy="id_producto")
	 */
	private $facturas;

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
	 * @ORM\Column(type="float")
	 */
	private $xcientoganancia;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $cantidad_taller;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $cantidad_inventario;

    public function getCantidadTaller(): ?int
    {
        return $this->cantidad_taller;
    }

    public function setCantidadTaller(int $cantidad_taller): self
    {
        $this->cantidad_taller = $cantidad_taller;

        return $this;
    }

    public function getCantidadInventario(): ?int
    {
        return $this->cantidad_inventario;
    }

    public function setCantidadInventario(int $cantidad_inventario): self
    {
        $this->cantidad_inventario = $cantidad_inventario;

        return $this;
    }

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $active;

	public function __construct()
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            	{
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

	public function __toString()
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            	{
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            		return $this->marca;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            	}

    /**
     * @return Collection|FacturasProducto[]
     */
    public function getFacturas(): Collection
    {
        return $this->facturas;
    }

    public function addFactura(FacturasProducto $factura): self
    {
        if (!$this->facturas->contains($factura)) {
            $this->facturas[] = $factura;
            $factura->setIdProducto($this);
        }

        return $this;
    }

    public function removeFactura(FacturasProducto $factura): self
    {
        if ($this->facturas->removeElement($factura)) {
            // set the owning side to null (unless already changed)
            if ($factura->getIdProducto() === $this) {
                $factura->setIdProducto(null);
            }
        }

        return $this;
    }

    public function getXcientoganancia(): ?float
    {
        return $this->xcientoganancia;
    }

    public function setXcientoganancia(float $xcientoganancia): self
    {
        $this->xcientoganancia = $xcientoganancia;

        return $this;
    }
}
