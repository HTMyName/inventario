<?php

namespace App\Entity;

use App\Repository\LogsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogsRepository::class)
 */
class Logs
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $fecha;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $detalles;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="logs")
	 * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id")
	 */
	private $id_cliente;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="logs")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	private $id_user;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Facturas", inversedBy="logs")
	 * @ORM\JoinColumn(name="factura_id", referencedColumnName="id")
	 */
	private $id_factura;

	/**
	 * @ORM\Column(type="text")
	 */
	private $tipo;

	public function __construct()
                                       	{
                                       		$this->id_cliente = new ArrayCollection();
                                       		$this->id_user = new ArrayCollection();
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

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDetalles(): ?string
    {
        return $this->detalles;
    }

    public function setDetalles(string $detalles): self
    {
        $this->detalles = $detalles;

        return $this;
    }

	public function addIdCliente(Cliente $idCliente): self
                                       	{
                                       		if (!$this->id_cliente->contains($idCliente)) {
                                       			$this->id_cliente[] = $idCliente;
                                       			$idCliente->setLogs($this);
                                       		}

                                       		return $this;
                                       	}

	public function removeIdCliente(Cliente $idCliente): self
                                       	{
                                       		if ($this->id_cliente->removeElement($idCliente)) {
                                       			// set the owning side to null (unless already changed)
                                       			if ($idCliente->getLogs() === $this) {
                                       				$idCliente->setLogs(null);
                                       			}
                                       		}

                                       		return $this;
                                       	}

	public function addIdUser(User $idUser): self
                                       	{
                                       		if (!$this->id_user->contains($idUser)) {
                                       			$this->id_user[] = $idUser;
                                       			$idUser->setLogs($this);
                                       		}

                                       		return $this;
                                       	}

	public function removeIdUser(User $idUser): self
                                       	{
                                       		if ($this->id_user->removeElement($idUser)) {
                                       			// set the owning side to null (unless already changed)
                                       			if ($idUser->getLogs() === $this) {
                                       				$idUser->setLogs(null);
                                       			}
                                       		}

                                       		return $this;
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

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}
