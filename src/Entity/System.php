<?php

namespace App\Entity;

use App\Repository\SystemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=SystemRepository::class)
 * @Vich\Uploadable
 */
class System
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $pagename;

	/**
	 * @ORM\Column(type="float")
	 */
	private $winservice;

	/**
	 * @ORM\Column(type="float")
	 */
	private $winproduct;

	/**
	 * @ORM\Column(type="float")
	 */
	private $inversion;

	/**
	 * @ORM\Column(type="float")
	 */
	private $recuperado;

	/**
	 * @ORM\Column(type="float")
	 */
	private $ganancia;

	/**
	 * @ORM\Column(type="float")
	 */
	private $gastos;

	/**
	 * @ORM\Column(type="float")
	 */
	private $banco;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $year_start;

	/**
	 * NOTE: This is not a mapped field of entity metadata, just a simple property.
	 *
	 * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName", size="imageSize")
	 *
	 * @var File|null
	 */
	private $imageFile;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 *
	 * @var string|null
	 */
	private $imageName;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 *
	 * @var int|null
	 */
	private $imageSize;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 *
	 * @var \DateTimeInterface|null
	 */
	private $updatedAt;

	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
	 */
	public function setImageFile(?File $imageFile = null): void
	{
		$this->imageFile = $imageFile;

		if (null !== $imageFile) {
			// It is required that at least one field changes if you are using doctrine
			// otherwise the event listeners won't be called and the file is lost
			$this->updatedAt = new \DateTimeImmutable();
		}
	}

	public function getImageFile(): ?File
	{
		return $this->imageFile;
	}

	public function getImageName(): ?string
	{
		return $this->imageName;
	}

	public function setImageName(string $imageName): self
	{
		$this->imageName = $imageName;

		return $this;
	}

	public function getImageSize(): ?int
	{
		return $this->imageSize;
	}

	public function setImageSize(int $imageSize): self
	{
		$this->imageSize = $imageSize;

		return $this;
	}

	public function getUpdatedAt(): ?\DateTimeInterface
	{
		return $this->updatedAt;
	}

	public function setUpdatedAt(\DateTimeInterface $updatedAt): self
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}

	public function getPagename(): ?string
	{
		return $this->pagename;
	}

	public function setPagename(string $pagename): self
	{
		$this->pagename = $pagename;

		return $this;
	}

	public function getWinservice(): ?float
	{
		return $this->winservice;
	}

	public function setWinservice(float $winservice): self
	{
		$this->winservice = $winservice;

		return $this;
	}

	public function getWinproduct(): ?float
	{
		return $this->winproduct;
	}

	public function setWinproduct(float $winproduct): self
	{
		$this->winproduct = $winproduct;

		return $this;
	}

	public function getInversion(): ?float
	{
		return $this->inversion;
	}

	public function setInversion(float $inversion): self
	{
		$this->inversion = $inversion;

		return $this;
	}

	public function getRecuperado(): ?float
	{
		return $this->recuperado;
	}

	public function setRecuperado(float $recuperado): self
	{
		$this->recuperado = $recuperado;

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

	public function getYearStart(): ?int
	{
		return $this->year_start;
	}

	public function setYearStart(int $year_start): self
	{
		$this->year_start = $year_start;

		return $this;
	}

	public function getGastos(): ?float
	{
		return $this->gastos;
	}

	public function setGastos(float $gastos): self
	{
		$this->gastos = $gastos;

		return $this;
	}

	public function getBanco(): ?float
	{
		return $this->banco;
	}

	public function setBanco(float $banco): self
	{
		$this->banco = $banco;

		return $this;
	}
}
