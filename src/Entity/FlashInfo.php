<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

use App\Repository\FlashInfoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FlashInfoRepository::class)]
#[ApiResource(
)]
class FlashInfo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[GroupS('FlashInfo')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[GroupS('FlashInfo')]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[GroupS('FlashInfo')]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToOne(inversedBy: 'FlashInfo')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MaisonDeCulte $maisonDeCulte = null;

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

    public function getMaisonDeCulte(): ?MaisonDeCulte
    {
        return $this->maisonDeCulte;
    }

    public function setMaisonDeCulte(?MaisonDeCulte $maisonDeCulte): static
    {
        $this->maisonDeCulte = $maisonDeCulte;

        return $this;
    }
}
