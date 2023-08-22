<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Paiement::class)]
    private Collection $Paiement;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MaisonDeCulte::class)]
    private Collection $MaisonDeCulte;

    public function __construct()
    {
        $this->Paiement = new ArrayCollection();
        $this->MaisonDeCulte = new ArrayCollection();
    }


    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Paiement>
     */
    public function getPaiement(): Collection
    {
        return $this->Paiement;
    }

    public function addPaiement(Paiement $paiement): static
    {
        if (!$this->Paiement->contains($paiement)) {
            $this->Paiement->add($paiement);
            $paiement->setUser($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): static
    {
        if ($this->Paiement->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getUser() === $this) {
                $paiement->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MaisonDeCulte>
     */
    public function getMaisonDeCulte(): Collection
    {
        return $this->MaisonDeCulte;
    }

    public function addMaisonDeCulte(MaisonDeCulte $maisonDeCulte): static
    {
        if (!$this->MaisonDeCulte->contains($maisonDeCulte)) {
            $this->MaisonDeCulte->add($maisonDeCulte);
            $maisonDeCulte->setUser($this);
        }

        return $this;
    }

    public function removeMaisonDeCulte(MaisonDeCulte $maisonDeCulte): static
    {
        if ($this->MaisonDeCulte->removeElement($maisonDeCulte)) {
            // set the owning side to null (unless already changed)
            if ($maisonDeCulte->getUser() === $this) {
                $maisonDeCulte->setUser(null);
            }
        }

        return $this;
    }


}
