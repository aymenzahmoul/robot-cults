<?php

namespace App\Entity;

use App\Repository\MaisonDeCulteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;

#[ORM\Entity(repositoryClass: MaisonDeCulteRepository::class)]
#[ApiResource(
    itemOperations: [
        'get',
        'custom_post' => [
            'method' => 'POST',
            'path' => '/maison/{userid}',
            'controller' => MaisonDeCulteController::class . '::customPostAction',
            'swagger_context' => [
                'parameters' => [
                    [
                        'name' => 'userid',
                        'in' => 'path',
                        'required' => true,
                        'type' => 'integer',
                        'description' => 'ID of the User entity (Owner)',
                    ],
                ],
            ],
        ],
        'put',
        'delete',
    ],
)]
class MaisonDeCulte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[GroupS('MaisonDeCulte')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[GroupS('MaisonDeCulte')]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[GroupS('MaisonDeCulte')]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[GroupS('MaisonDeCulte')]
    private ?string $responsable = null;

    #[ORM\OneToMany(mappedBy: 'maisonDeCulte', targetEntity: Projet::class)]
    private Collection $projets;

    #[ORM\OneToMany(mappedBy: 'maisonDeCulte', targetEntity: FlashInfo::class)]
    private Collection $FlashInfo;

    #[ORM\ManyToOne(inversedBy: 'MaisonDeCulte')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

   

    public function __construct()
    {
        $this->projets = new ArrayCollection();
        $this->FlashInfo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(string $responsable): static
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * @return Collection<int, Projet>
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): static
    {
        if (!$this->projets->contains($projet)) {
            $this->projets->add($projet);
            $projet->setMaisonDeCulte($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): static
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getMaisonDeCulte() === $this) {
                $projet->setMaisonDeCulte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FlashInfo>
     */
    public function getFlashInfo(): Collection
    {
        return $this->FlashInfo;
    }

    public function addFlashInfo(FlashInfo $flashInfo): static
    {
        if (!$this->FlashInfo->contains($flashInfo)) {
            $this->FlashInfo->add($flashInfo);
            $flashInfo->setMaisonDeCulte($this);
        }

        return $this;
    }

    public function removeFlashInfo(FlashInfo $flashInfo): static
    {
        if ($this->FlashInfo->removeElement($flashInfo)) {
            // set the owning side to null (unless already changed)
            if ($flashInfo->getMaisonDeCulte() === $this) {
                $flashInfo->setMaisonDeCulte(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }


}
