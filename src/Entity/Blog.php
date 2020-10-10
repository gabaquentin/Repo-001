<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 */
class Blog
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
     * @ORM\Column(type="string", length=255)
     */
    private $courte_description;

    /**
     * @ORM\Column(type="text")
     */
    private $grande_description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_createur;

    /**
     * @ORM\Column(type="integer")
     */
    private $vues;

    /**
     * @ORM\Column(type="integer")
     */
    private $commentaires;

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

    public function getCourteDescription(): ?string
    {
        return $this->courte_description;
    }

    public function setCourteDescription(string $courte_description): self
    {
        $this->courte_description = $courte_description;

        return $this;
    }

    public function getGrandeDescription(): ?string
    {
        return $this->grande_description;
    }

    public function setGrandeDescription(string $grande_description): self
    {
        $this->grande_description = $grande_description;

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

    public function getIdCreateur(): ?int
    {
        return $this->id_createur;
    }

    public function setIdCreateur(int $id_createur): self
    {
        $this->id_createur = $id_createur;

        return $this;
    }

    public function getVues(): ?int
    {
        return $this->vues;
    }

    public function setVues(int $vues): self
    {
        $this->vues = $vues;

        return $this;
    }

    public function getCommentaires(): ?int
    {
        return $this->commentaires;
    }

    public function setCommentaires(int $commentaires): self
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDateString(): ?string
    {
        $stringValue = $this->date->format('Y-m-d H:i:s');
        return $stringValue;
    }
}
