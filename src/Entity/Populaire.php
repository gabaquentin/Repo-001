<?php

namespace App\Entity;

use App\Repository\PopulaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PopulaireRepository::class)
 */
class Populaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @Groups({"show_list"})
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"show_list"})
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @Groups({"show_list"})
     * @ORM\Column(type="string", length=255)
     */
    private $lien;

    /**
     * @Groups({"show_list"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Groups({"show_list"})
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDate(): ?string
    {
        $stringValue = $this->date->format('Y-m-d H:i:s');
        return $stringValue;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }
}
