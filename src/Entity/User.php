<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 */
	private $username;

	/**
	 * @ORM\Column(type="string", length=180)
	 */
	private $name;

	/**
	 * @ORM\Column(type="float")
	 */
	private $payS;

	/**
	 * @ORM\Column(type="float")
	 */
	private $payV;

	/**
	 * @ORM\Column(type="float")
	 */
	private $payTotal;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $active;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Logs", mappedBy="id_user")
	 */
	private $logs;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Facturas", mappedBy="id_user")
	 */
	private $facturas;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\ToDo", mappedBy="id_user")
	 */
	private $todo;

	/**
	 * @ORM\Column(type="json")
	 */
	private $roles = [];

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 */
	private $password;

	/**
	 * @ORM\Column(type="float")
	 */
	private $caja;

	public function __construct()
	{
		$this->logs = new ArrayCollection();
		$this->facturas = new ArrayCollection();
		$this->todo = new ArrayCollection();
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

	public function getPayS(): ?float
	{
		return $this->payS;
	}

	public function setPayS(float $payS): self
	{
		$this->payS = $payS;

		return $this;
	}

	public function getPayV(): ?float
	{
		return $this->payV;
	}

	public function setPayV(float $payV): self
	{
		$this->payV = $payV;

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

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getUsername(): ?string
	{
		return $this->username;
	}

	public function setUsername(string $username): self
	{
		$this->username = $username;

		return $this;
	}

	public function getRoles(): ?array
	{
		return $this->roles;
	}

	public function setRoles(array $roles): self
	{
		$this->roles = $roles;

		return $this;
	}

	public function getPassword(): ?string
	{
		return $this->password;
	}

	public function setPassword(string $password): self
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getSalt()
	{
		// not needed when using the "bcrypt" algorithm in security.yaml
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
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
			$log->setIdUser($this);
		}

		return $this;
	}

	public function removeLog(Logs $log): self
	{
		if ($this->logs->removeElement($log)) {
			// set the owning side to null (unless already changed)
			if ($log->getIdUser() === $this) {
				$log->setIdUser(null);
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
			$factura->setIdUser($this);
		}

		return $this;
	}

	public function removeFactura(Facturas $factura): self
	{
		if ($this->facturas->removeElement($factura)) {
			// set the owning side to null (unless already changed)
			if ($factura->getIdUser() === $this) {
				$factura->setIdUser(null);
			}
		}

		return $this;
	}

	public function __toString()
	{
		return $this->name;
	}

	public function getPayTotal(): ?float
	{
		return $this->payTotal;
	}

	public function setPayTotal(float $payTotal): self
	{
		$this->payTotal = $payTotal;

		return $this;
	}

	/**
	 * @return Collection|ToDo[]
	 */
	public function getTodo(): Collection
	{
		return $this->todo;
	}

	public function addTodo(ToDo $todo): self
	{
		if (!$this->todo->contains($todo)) {
			$this->todo[] = $todo;
			$todo->setIdUser($this);
		}

		return $this;
	}

	public function removeTodo(ToDo $todo): self
	{
		if ($this->todo->removeElement($todo)) {
			// set the owning side to null (unless already changed)
			if ($todo->getIdUser() === $this) {
				$todo->setIdUser(null);
			}
		}

		return $this;
	}

	public function getCaja(): ?float
	{
		return $this->caja;
	}

	public function setCaja(float $caja): self
	{
		$this->caja = $caja;

		return $this;
	}

}
