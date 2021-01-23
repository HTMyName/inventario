<?php

namespace App\Entity;

use App\Repository\LogsRepository;
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
	 * @ORM\ManyToOne(targetEntity="App\Entity\Cliente", inversedBy="id")
	 */
	private $id_cliente;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="id")
	 */
	private $id_user;

	/**
	 * @return mixed
	 */
	public function getIdCliente()
	{
		return $this->id_cliente;
	}

	/**
	 * @param mixed $id_cliente
	 */
	public function setIdCliente($id_cliente): void
	{
		$this->id_cliente = $id_cliente;
	}

	/**
	 * @return mixed
	 */
	public function getIdUser()
	{
		return $this->id_user;
	}

	/**
	 * @param mixed $id_user
	 */
	public function setIdUser($id_user): void
	{
		$this->id_user = $id_user;
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
}
