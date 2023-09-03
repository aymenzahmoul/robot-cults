<?php

namespace App\Entity;

use App\Repository\ContributionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ContributionRepository::class)]
#[ApiResource(    
    normalizationContext: ['groups' => ['oui']],
       )]
class Contribution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('oui')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('oui')]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups('oui')]
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $typeContribution = null;

    #[ORM\ManyToOne(inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Projet $projet = null;

  
    #[ORM\ManyToOne(inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTypeContribution(): ?string
    {
        return $this->typeContribution;
    }

    public function setTypeContribution(string $typeContribution): static
    {
        $this->typeContribution = $typeContribution;

        return $this;
    }
    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): static
    {
        $this->projet = $projet;

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
