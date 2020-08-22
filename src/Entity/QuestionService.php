<?php

namespace App\Entity;

use App\Repository\QuestionServiceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionServiceRepository::class)
 */
class QuestionService
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Service
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Service")
     * @ORM\JoinColumn(name="service", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $service;

    /**
     * @ORM\Column(name="question",type="text", nullable=false)
     */
    private $question;

    /**
     * @ORM\Column(name="reponses",type="text", nullable=false)
     */
    private $reponses;

    /**
     * @ORM\Column(name="typeQuestion",type="string", length=3, nullable=false)
     */
    private $typeQuestion;

    /**
     * @ORM\Column(name="autre",type="string",length=3)
     */
    private $autre = "non";

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Service
     */
    public function getService(): Service
    {
        return $this -> service;
    }

    /**
     * @param Service $service
     */
    public function setService(Service $service): void
    {
        $this -> service = $service;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this -> question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question): void
    {
        $this -> question = $question;
    }

    /**
     * @return mixed
     */
    public function getReponses()
    {
        return $this -> reponses;
    }

    /**
     * @param mixed $reponses
     */
    public function setReponses($reponses): void
    {
        $this -> reponses = $reponses;
    }

    /**
     * @return mixed
     */
    public function getTypeQuestion()
    {
        return $this -> typeQuestion;
    }

    /**
     * @param mixed $typeQuestion
     */
    public function setTypeQuestion($typeQuestion): void
    {
        $this -> typeQuestion = $typeQuestion;
    }

    /**
     * @return bool
     */
    public function isAutre(): bool
    {
        return $this -> autre;
    }

    /**
     * @param bool $autre
     */
    public function setAutre(bool $autre): void
    {
        $this -> autre = $autre;
    }

}
