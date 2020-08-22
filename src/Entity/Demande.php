<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DemandeRepository::class)
 */
class Demande
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
    private $localisation;

    /**
     * @ORM\Column(type="time", length=255)
     */
    private $heure;

    /**
     * @return mixed
     */
    public function getHeure()
    {
        return $this->heure;
    }

    /**
     * @param mixed $heure
     */
    public function setHeure($heure): void
    {
        $this->heure = $heure;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @ORM\Column(name="description",type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo3;


    public function getPhoto1():? string
    {
        return $this->photo1;
    }

    public function setPhoto1($photo1): self
    {
        $this->photo1 = $photo1;
        return $this;
    }


    public function getPhoto2():? string
    {
        return $this->photo2;
    }


    public function setPhoto2($photo2): self
    {
        $this->photo2 = $photo2;
        return $this;
    }


    public function getPhoto3():? string
    {
        return $this->photo3;
    }


    public function setPhoto3($photo3): self
    {
        $this->photo3 = $photo3;
        return $this;
    }


    public function getPhoto4():? string
    {
        return $this->photo4;
    }

    public function setPhoto4($photo4): self
    {
        $this->photo4 = $photo4;

        return $this;
    }

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo4;

    /**
     * @ORM\ManyToOne(targetEntity=Service::class, inversedBy="demandes")
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPhotos(): ?string
    {
        return $this->photos;
    }

    public function setPhotos(string $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }
}
