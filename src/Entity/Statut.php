<?php

namespace App\Entity;

use App\Repository\StatutRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $statique = null;

    #[ORM\Column(length: 255)]
    private ?string $enCours = null;

    #[ORM\Column(length: 255)]
    private ?string $terminer = null;

    #[ORM\Column(length: 255)]
    private ?string $suspendu = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatique(): ?string
    {
        return $this->statique;
    }

    public function setStatique(string $statique): static
    {
        $this->statique = $statique;

        return $this;
    }

    public function getEnCours(): ?string
    {
        return $this->enCours;
    }

    public function setEnCours(string $enCours): static
    {
        $this->enCours = $enCours;

        return $this;
    }

    public function getTerminer(): ?string
    {
        return $this->terminer;
    }

    public function setTerminer(string $terminer): static
    {
        $this->terminer = $terminer;

        return $this;
    }

    public function getSuspendu(): ?string
    {
        return $this->suspendu;
    }

    public function setSuspendu(string $suspendu): static
    {
        $this->suspendu = $suspendu;

        return $this;
    }
}
