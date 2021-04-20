<?php

namespace App\Entity;

use App\Repository\ToDoRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ToDoRepository::class)
 */
class ToDo
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $completed;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $texto;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $fecha;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="todo")
	 * @ORM\JoinColumn(name="id_user", referencedColumnName="id")
	 */
	private $id_user;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $visible;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getCompleted(): ?bool
	{
		return $this->completed;
	}

	public function setCompleted(bool $completed): self
	{
		$this->completed = $completed;

		return $this;
	}

	public function getTexto(): ?string
	{
		return $this->texto;
	}

	public function setTexto(string $texto): self
	{
		$this->texto = $texto;

		return $this;
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

	public function getIdUser(): ?User
	{
		return $this->id_user;
	}

	public function setIdUser(?User $id_user): self
	{
		$this->id_user = $id_user;

		return $this;
	}

	public function getVisible(): ?bool
	{
		return $this->visible;
	}

	public function setVisible(bool $visible): self
	{
		$this->visible = $visible;

		return $this;
	}
}
