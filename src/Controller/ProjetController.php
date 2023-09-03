<?php

namespace App\Controller;


use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\MaisonDeCulteRepository;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
#[Route('/api')]
class ProjetController extends AbstractController
{


    #[Route('/test', name: 'app_projet_index', methods: ['GET'])]
    public function index(ProjetRepository $projetRepository): Response
    {
        return $this->render('projet/index.html.twig', [
            'projets' => $projetRepository->findAll(),
        ]);
    }

    #[Route('/projet/create/{maisondeculteid}', name: 'api_projet_create', methods: ['POST'])]
    public function createProjet(int $maisondeculteid, ManagerRegistry $doctrine, Request $request, MaisonDeCulteRepository $maisondeculterRepository): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
    
        $titre = $decoded->titre;
        $description = $decoded->description;
        $montant = $decoded->montant;
    
        // Convert date strings to DateTime objects
        $dateDebut = new \DateTime($decoded->dateDebut);
        $dateFin = new \DateTime($decoded->dateFin);
    
        // Retrieve the MaisonDeCulte entity based on the provided maisondeculteid
        $maisonDeCulte = $maisondeculterRepository->find($maisondeculteid);
    
        if (!$maisonDeCulte) {
            throw $this->createNotFoundException('MaisonDeCulte not found');
        }
    
        $projet = new Projet();
        $projet->setMaisonDeCulte($maisonDeCulte);
        $projet->setTitre($titre);
        $projet->setDescription($description);
        $projet->setMontant($montant);
        $projet->setDateDebut($dateDebut);
        $projet->setDateFin($dateFin);
    
        // Additional logic specific to Projet entity
    
        $entityManager->persist($projet);
        $entityManager->flush();
    
        return $this->json(['message' => 'Projet created successfully']);
    }
    
    

    #[Route('/{id}', name: 'app_projet_show', methods: ['GET'])]
    public function show(Projet $projet): Response
    {
        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
        ]);
    }

    #[Route('/projet/update/{id}', name: 'projet_update', methods: ['PUT'])]
    public function updateProjet(int $id, Request $request, ProjetRepository $projetRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $projet = $projetRepository->find($id);
    
        if (!$projet) {
            throw $this->createNotFoundException('Projet not found');
        }
    
        $decoded = json_decode($request->getContent());
    
        // Update properties of the $projet entity
        $projet->setTitre($decoded->titre);
        $projet->setDescription($decoded->description);
        $projet->setMontant($decoded->montant);
        $projet->setDateDebut(new \DateTime($decoded->dateDebut)); // Assuming date format is 'Y-m-d'
        $projet->setDateFin(new \DateTime($decoded->dateFin)); // Assuming date format is 'Y-m-d'
    
        $entityManager->flush();
    
        return $this->json(['message' => 'Projet updated successfully']);
    }

    #[Route('/projet/delete/{id}', name: 'projet_delete', methods: ['DELETE'])]
    public function deleteProjet(int $id, ProjetRepository $projetRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $projet = $projetRepository->find($id);

        if (!$projet) {
            throw $this->createNotFoundException('Projet not found');
        }

        $entityManager->remove($projet);
        $entityManager->flush();
    
        return new JsonResponse(['message' => 'Projet deleted successfully']);
    }
    
    
}
