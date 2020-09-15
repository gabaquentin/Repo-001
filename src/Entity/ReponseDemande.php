<?php

namespace App\Entity;

use App\Repository\ReponseDemandeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReponseDemandeRepository::class)
 */
class ReponseDemande
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"group1"})
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Demande
     */
    public function getDemande(): Demande
    {
        return $this->demande;
    }

    /**
     * @param Demande $demande
     */
    public function setDemande(Demande $demande): void
    {
        $this->demande = $demande;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question): void
    {
        $this->question = $question;
    }

    /**
     * @return mixed
     */
    public function getReponses()
    {
        return $this->reponses;
    }

    /**
     * @param mixed $reponses
     */
    public function setReponses($reponses): void
    {
        $this->reponses = $reponses;
    }

    /**
     * @var Service
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Demande")
     * @ORM\JoinColumn(name="demande_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $demande;

    /**
     * @ORM\Column(name="question",type="text", nullable=false)
     */
    private $question;


    /**
     * @ORM\Column(name="reponses",type="text", nullable=false)
     * @Groups({"group1"})
     */
    private $reponses;


}
