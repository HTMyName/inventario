<?php

namespace App\Entity;

use App\Repository\ServicioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;

/**
 * @ORM\Entity(repositoryClass=ServicioRepository::class)
 */
class Servicio
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\FacturasServicio", mappedBy="id_servicio")
	 */
	private $facturas;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $detalles;

	/**
	 * @ORM\Column(type="float")
	 */
	private $precio;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDetalles(): ?string
    {
        return $this->detalles;
    }

    public function setDetalles(string $detalles): self
    {
        $this->detalles = $detalles;

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

	public function __toString()
                                                                                                                                                                                                                        	{
                                                                                                                                                                                                                        		return $this->name;
                                                                                                                                                                                                                        	}

    /**
     * @return \Doctrine\Common\Collections\Collection|FacturasServicio[]
     */
    public function getFacturas(): \Doctrine\Common\Collections\Collection
    {
        return $this->facturas;
    }

    public function addFactura(FacturasServicio $factura): self
    {
        if (!$this->facturas->contains($factura)) {
            $this->facturas[] = $factura;
            $factura->setIdServicio($this);
        }

        return $this;
    }

    public function removeFactura(FacturasServicio $factura): self
    {
        if ($this->facturas->removeElement($factura)) {
            // set the owning side to null (unless already changed)
            if ($factura->getIdServicio() === $this) {
                $factura->setIdServicio(null);
            }
        }

        return $this;
    }
}
