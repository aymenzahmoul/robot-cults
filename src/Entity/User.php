<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use ApiPlatform\Core\Annotation\ApiResource;
use PhpParser\Node\Stmt\For_;

#[ApiResource(
    collectionOperations: [],
    itemOperations: [
        'get',
        'change_role' => [
            'method' => 'PUT',
            'controller' => UserController::class,
            'path' => '/users/{id}/change-role/{newRole}',
            'deserialize' => false,
            'openapi_context' => [
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'required' => true,
                        'type' => 'integer',
                    ],
                    [
                        'name' => 'newRole',
                        'in' => 'path',
                        'required' => true,
                        'type' => 'string', // Change the type to 'string'
                        'enum' => ['role1', 'role2', 'role3'], // Define possible role values
                    ],
                ],
            ],
        ],
    ],
)]

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

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Contribution::class)]
    private Collection $Contribution;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: MaisonDeCulte::class)]
    private Collection $MaisonDeCulte;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'users')]
    private Collection $Role;



    public function __construct()
    {
        $this->Contribution = new ArrayCollection();
        $this->MaisonDeCulte = new ArrayCollection();
        $this->Role = new ArrayCollection();
      
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
     * @return Collection<int, Contribution>
     */
    public function getContribution(): Collection
    {
        return $this->Contribution;
    }

    public function addContribution(Contribution $contribution): static
    {
        if (!$this->Contribution->contains($contribution)) {
            $this->Contribution->add($contribution);
            $contribution->setUser($this);
        }

        return $this;
    }

    public function removePaiement(Contribution $contribution): static
    {
        if ($this->Contribution->removeElement($contribution)) {
            // set the owning side to null (unless already changed)
            if ($contribution->getUser() === $this) {
                $contribution->setUser(null);
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
   

    public function getRoles(): array
    {       
        $userRoles = $this->getRole();
        $roles = [];
        
        $roles[] = 'ROLE_USER';
        
        foreach ($userRoles as $userRole) {
           $roles[] = $userRole->getRoleName();
        }
        
        return array_unique($roles);
    }
    
  


    public function isAdmin(): bool
    {
        return in_array('ROLE_ADMIN', $this->roles);
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRole(): Collection
    {
        return $this->Role;
    }

    public function addRole(Role $role): static
    {
        if (!$this->Role->contains($role)) {
            $this->Role->add($role);
        }

        return $this;
    }

    public function removeRole(Role $role): static
    {
        $this->Role->removeElement($role);

        return $this;
    }

}
