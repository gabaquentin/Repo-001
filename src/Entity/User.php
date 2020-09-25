<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

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
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $departement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $partenariat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $local;

    /**
     * @ORM\Column(type="datetime", length=255)
     */
    private $creation;

    /**
     * @ORM\Column(type="blob")
     */
    private $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $boutique;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $domaine = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;

    /**
     * @ORM\OneToOne(targetEntity=Shipping::class)
     */
    private $livraison;

    /**
     * @ORM\OneToOne(targetEntity=Billing::class)
     */
    private $paiement;

    /**
     * @ORM\Column(type="integer", options={"comment":"enable shipping address"})
     */
    private $esa;

    /**
     * @ORM\Column(type="boolean")
     */
    private $phone_verified;

    /**
     * @ORM\OneToOne(targetEntity=Pack::class, inversedBy="user", cascade={"persist", "remove"})
     */
    private $pack;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"comment":"partner status"})
     */
    private $ps;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"comment":"date partnership"})
     */
    private $dp;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"comment":"admin lock"})
     */
    private $al;

    public function __construct()
    {
        $this->creation = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return in_array("ROLE_ADMIN",$this->getRoles());
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(?string $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getPartenariat(): ?string
    {
        return $this->partenariat;
    }

    public function setPartenariat(?string $partenariat): self
    {
        $this->partenariat = $partenariat;

        return $this;
    }

    public function getLastLogin(): ?string
    {
        return $this->last_login;
    }

    public function setLastLogin(?string $last_login): self
    {
        $this->last_login = $last_login;

        return $this;
    }

    public function getLocal(): ?string
    {
        return $this->local;
    }

    public function setLocal(string $local): self
    {
        $this->local = $local;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): self
    {
        $this->creation = $creation;

        return $this;
    }

    public function getImage()
    {
        return stream_get_contents($this->image);
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }


    public function getLogo()
    {
        return stream_get_contents($this->logo);
    }

    public function setLogo($logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getBoutique(): ?string
    {
        return $this->boutique;
    }

    public function setBoutique(?string $boutique): self
    {
        $this->boutique = $boutique;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDomaine(): ?array
    {
        return $this->domaine;
    }

    public function setDomaine(?array $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLivraison()
    {
        return $this->livraison;
    }

    /**
     * @param mixed $livraison
     */
    public function setLivraison($livraison): void
    {
        $this->livraison = $livraison;
    }

    /**
     * @return mixed
     */
    public function getPaiement()
    {
        return $this->paiement;
    }

    /**
     * @param mixed $paiement
     */
    public function setPaiement($paiement): void
    {
        $this->paiement = $paiement;
    }

    public function getEsa(): ?int
    {
        return $this->esa;
    }

    public function setEsa(int $esa): self
    {
        $this->esa = $esa;

        return $this;
    }

    public function getPack(): ?Pack
    {
        return $this->pack;
    }

    public function setPack(?Pack $pack): self
    {
        $this->pack = $pack;


        return $this;
    }

    public function getPhoneVerified(): ?bool
    {
        return $this->phone_verified;
    }

    public function setPhoneVerified(bool $phone_verified): self
    {
        $this->phone_verified = $phone_verified;

        return $this;
    }

    public function getPs(): ?bool
    {
        return $this->ps;
    }

    public function setPs(?bool $ps): self
    {
        $this->ps = $ps;

        return $this;
    }

    public function getDp(): ?string
    {
        return $this->dp;
    }

    public function setDp(?string $dp): self
    {
        $this->dp = $dp;

        return $this;
    }

    public function getAl(): ?bool
    {
        return $this->al;
    }

    public function setAl(?bool $al): self
    {
        $this->al = $al;

        return $this;
    }

}
