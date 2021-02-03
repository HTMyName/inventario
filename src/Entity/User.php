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
	 * @ORM\Column(type="boolean")
	 */
	private $active;

	/**
	 * @ORM\Column(type="float")
	 */
	private $venta_direct;

	/**
	 * @ORM\Column(type="float")
	 */
	private $servicio_direct;

	/**
	 * @ORM\Column(type="float")
	 */
	private $venta_indirect;

	/**
	 * @ORM\Column(type="float")
	 */
	private $servicio_indirect;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Logs", mappedBy="id_user")
	 */
	private $logs;

    public function getVentaIndirect(): ?float
    {
        return $this->venta_indirect;
    }

    public function setVentaIndirect(float $venta_indirect): self
    {
        $this->venta_indirect = $venta_indirect;

        return $this;
    }

    public function getServicioIndirect(): ?float
    {
        return $this->servicio_indirect;
    }

    public function setServicioIndirect(float $servicio_indirect): self
    {
        $this->servicio_indirect = $servicio_indirect;

        return $this;
    }

    public function getVentaDirect(): ?float
    {
        return $this->venta_direct;
    }

    public function setVentaDirect(float $venta_direct): self
    {
        $this->venta_direct = $venta_direct;

        return $this;
    }

    public function getServicioDirect(): ?float
    {
        return $this->servicio_direct;
    }

    public function setServicioDirect(float $servicio_direct): self
    {
        $this->servicio_direct = $servicio_direct;

        return $this;
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


	/**
	 * @ORM\Column(type="json")
	 */
	private $roles = [];

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 */
	private $password;

	public function __construct()
                                                                                                                                                            	{
                                                                                                                                                            		$this->logs = new ArrayCollection();
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

	public function setLogs(?Logs $logs): self
                                                                                                                                                            	{
                                                                                                                                                            		$this->logs = $logs;
                                                                                                                                                            
                                                                                                                                                            		return $this;
                                                                                                                                                            	}
}
