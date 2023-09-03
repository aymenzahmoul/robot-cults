<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use App\Repository\PaiementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementRepository::class)]
#[ApiResource(
    itemOperations: [
        'get',
        'custom_post' => [
            'method' => 'POST',
            'path' => '/paiements/custom_post/{projetid}/{userid}',
            'controller' => PaiementController::class . '::customPostAction',
            'swagger_context' => [
                'parameters' => [
                    [
                        'name' => 'projetid',
                        'in' => 'path',
                        'required' => true,
                        'type' => 'integer',
                        'description' => 'ID of the Projet entity',
                    ],
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

class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $date = null;

 

    public function getId(): ?int
    {
        return $this->id;
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

   
}
