<?php

namespace App\Controller;

use App\Entity\Statut;
use App\Form\StatutType;
use App\Repository\ProjetRepository;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class StatutController extends AbstractController
{
    #[Route('/', name: 'app_statut_index', methods: ['GET'])]
    public function index(StatutRepository $statutRepository): Response
    {
        return $this->render('statut/index.html.twig', [
            'statuts' => $statutRepository->findAll(),
        ]);
    }

    #[Route('/statut/create/{projetid}', name: 'api_statut_create', methods: ['POST'])]
    public function createStatut(int $projetid, ManagerRegistry $doctrine, Request $request, ProjetRepository $projetRepository): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
    
      
        $statique = $decoded->statique;
        $enCours = $decoded->enCours;
        $terminer = $decoded->terminer;
        $suspendu = $decoded->suspendu;
        // Convertir les chaînes de date en objets DateTime
    
        // Récupérer l'entité Projet en fonction de l'ID fourni dans projetid
        $projet = $projetRepository->find($projetid);
    
        if (!$projet) {
            throw $this->createNotFoundException('Projet introuvable');
        }
    
        $statut = new Statut();
        $statut->setProjet($projet);
        $statut->setStatique($statique);
        $statut->setEnCours($enCours);
        $statut->setTerminer($terminer);
        $statut->setSuspendu($suspendu);
     
    
        // Logique supplémentaire spécifique à l'entité Statut
    
        $entityManager->persist($statut);
        $entityManager->flush();
    
        return $this->json(['message' => 'Statut créé avec succès']);
    }
    

    #[Route('/{id}', name: 'app_statut_show', methods: ['GET'])]
    public function show(Statut $statut): Response
    {
        return $this->render('statut/show.html.twig', [
            'statut' => $statut,
        ]);
    }

    #[Route('/statut/update/{id}', name: 'api_statut_update', methods: ['PUT'])]
    public function updateStatut(int $id, Request $request, StatutRepository $statutRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $decoded = json_decode($request->getContent());
    
        $statut = $statutRepository->find($id);
    
        if (!$statut) {
            throw $this->createNotFoundException('Statut introuvable');
        }
    
        $statut->setStatique($decoded->statique);
        $statut->setEnCours($decoded->enCours);
        $statut->setTerminer($decoded->terminer);
        $statut->setSuspendu($decoded->suspendu);
    
        // Autres mises à jour nécessaires
    
        $entityManager->flush();
    
        return $this->json(['message' => 'Statut mis à jour avec succès']);
    }

#[Route('/statut/delete/{id}', name: 'api_statut_delete', methods: ['DELETE'])]
public function deleteStatut(int $id, StatutRepository $statutRepository, EntityManagerInterface $entityManager): JsonResponse
{
    $statut = $statutRepository->find($id);

    if (!$statut) {
        throw $this->createNotFoundException('Statut introuvable');
    }

    // Supprimer le statut de la base de données
    $entityManager->remove($statut);
    $entityManager->flush();

    return $this->json(['message' => 'Statut supprimé avec succès']);
}
}
