<?php

namespace App\Entity;

use App\Repository\DonateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonateurRepository::class)]
class Donateur extends User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    
    public function getId(): ?int
    {
        return $this->id;
    }

  


  

}
