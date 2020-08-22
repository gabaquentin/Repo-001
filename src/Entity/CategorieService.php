<?php

namespace App\Entity;

use App\Repository\CategorieServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieServiceRepository::class)
 */
class CategorieService
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=255, nullable=false)
     */
    private $img;

    /**
     * @var CategorieService
     *
     * @ORM\ManyToOne(targetEntity="CategorieService")
     * @ORM\JoinColumn(name="categorie_parent", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $categorieParent;

    /**
     * @ORM\OneToMany(targetEntity=Service::class, mappedBy="CategorieService")
     */
    private $services;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this -> description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this -> description = $description;
    }

    /**
     * @return string
     */
    public function getImg(): string
    {
        return $this -> img;
    }

    /**
     * @param string $img
     */
    public function setImg(string $img): void
    {
        $this -> img = $img;
    }

    /**
     * @return CategorieService
     */
    public function getCategorieParent(): ?CategorieService
    {
        return $this -> categorieParent;
    }

    /**
     * @param ?CategorieService $categorieParent
     */
    public function setCategorieParent(?CategorieService $categorieParent): void
    {
        $this -> categorieParent = $categorieParent;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
            $service->setCategorieService($this);
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
            // set the owning side to null (unless already changed)
            if ($service->getCategorieService() === $this) {
                $service->setCategorieService(null);
            }
        }

        return $this;
    }
}
