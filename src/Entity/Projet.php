<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
#[ORM\Entity(repositoryClass: ProjetRepository::class)]
#[ApiResource(
    itemOperations: [
        'get',
        'custom_post' => [
            'method' => 'POST',
            'path' => 'projet/new/test/{projetid}',
            'controller' => ProjetController::class . '::customPostAction',
            'swagger_context' => [
                'parameters' => [
                    [
                        'name' => 'projetid',
                        'in' => 'path',
                        'required' => true,
                        'type' => 'integer',
                        'description' => 'ID of the Projet entity',
                    ],
                ],
            ],
        ],
        'put',
        'delete',
    ],
)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    
    private ?int $id = null;

    #[ORM\Column(length: 255)]
   
    private ?string $titre = null;

    #[ORM\Column(length: 255)]

    private ?string $description = null;

    #[ORM\Column]
   
    private ?float $montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;



    #[ORM\ManyToOne(inversedBy: 'projets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MaisonDeCulte $maisonDeCulte = null;

    #[ORM\OneToMany(mappedBy: 'projet', targetEntity: Contribution::class)]
    private Collection $Contribution;

  

    

    public function __construct()
    {
        $this->Contribution = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
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

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

  

    public function getMaisonDeCulte(): ?maisonDeCulte
    {
        return $this->maisonDeCulte;
    }

    public function setMaisonDeCulte(?MaisonDeCulte $maisonDeCulte): static
    {
        $this->maisonDeCulte = $maisonDeCulte;

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
            $contribution->setProjet($this);
        }

        return $this;
    }

    public function removeContribution(Contribution $paiement): static
    {
        if ($this->Contribution->removeElement($paiement)) {
            // set the owning side to null (unless already changed)
            if ($paiement->getProjet() === $this) {
                $paiement->setProjet(null);
            }
        }

        return $this;
    }

 

   
}
