<?php

namespace App\Controller;

use App\Entity\MaisonDeCulte;
use App\Repository\MaisonDeCulteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api')]
class MaisonDeCulteController extends AbstractController
{


 
    #[Route('/test', name: 'app_maison_de_culte_index', methods: ['GET'])]
    public function index(MaisonDeCulteRepository $maisonDeCulteRepository): Response
    {
        return $this->render('maison_de_culte/index.html.twig', [
            'maison_de_cultes' => $maisonDeCulteRepository->findAll(),
        ]);
    }

    #[Route('/maison-de-culte/create/{userid}', name: 'maison_de_culte_create', methods: ['POST'])]
    public function create(int $userid, ManagerRegistry $doctrine, Request $request, UserRepository $userRepository): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
        $nom = $decoded->nom;
        $adresse = $decoded->adresse;
        $responsable = $decoded->responsable;
        // Retrieve the User entity based on the provided userid
        $user = $userRepository->find($userid);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
      
      
        $em->persist($user);
        $em->flush();

        $maisonDeCulte = new MaisonDeCulte();
        $maisonDeCulte->setUser($user);
        $maisonDeCulte->setNom($nom);
        $maisonDeCulte->setAdresse($adresse);
        $maisonDeCulte->setResponsable($responsable);

        
        // Additional logic specific to MaisonDeCulte entity
        
        $entityManager->persist($maisonDeCulte);
        $entityManager->flush();
    
        $em->flush();
    
        return $this->json(['message' => ' Successfully']);
    }
    

    #[Route('/maisons-de-culte', name: 'maisons_de_culte_list', methods: ['GET'])]
    public function list(MaisonDeCulteRepository $maisonDeCulteRepository): JsonResponse
    {
        // Récupérez la liste des maisons de culte depuis le repository
        $maisonsDeCulte = $maisonDeCulteRepository->findAll();
        dd($maisonsDeCulte);
        // Créez un tableau pour stocker les données des maisons de culte
        $maisonsDeCulteData = [];
    
        // Parcourez chaque maison de culte et ajoutez ses données au tableau
        foreach ($maisonsDeCulte as $maisonDeCulte) {
            $maisonData = [
                'id' => $maisonDeCulte->getId(),
                'nom' => $maisonDeCulte->getNom(),
                'adresse' => $maisonDeCulte->getAdresse(),
                'responsable' => $maisonDeCulte->getResponsable(),
                // Ajoutez d'autres propriétés si nécessaire
            ];
    
            $maisonsDeCulteData[] = $maisonData;
        }
    
        // Retournez la liste des maisons de culte sous forme de réponse JSON
        return $this->json($maisonsDeCulteData);
    }
    
    #[Route('/show/{id}', name: 'app_maison_de_culte_show', methods: ['GET'])]
    public function show(MaisonDeCulte $maisonDeCulte): Response
    {
        return $this->render('maison_de_culte/show.html.twig', [
            'maison_de_culte' => $maisonDeCulte,
        ]);
    }

    #[Route('/{id}/edit', name: 'maison_de_culte_edit', methods: ['PUT'])]
public function edit(int $id, Request $request, ManagerRegistry $doctrine, MaisonDeCulteRepository $maisonDeCulteRepository): JsonResponse
{
    $entityManager = $doctrine->getManager();
    $decoded = json_decode($request->getContent());
    $nom = $decoded->nom;
    $adresse = $decoded->adresse;
    $responsable = $decoded->responsable;

    // Retrieve the MaisonDeCulte entity based on the provided id
    $maisonDeCulte = $maisonDeCulteRepository->find($id);

    if (!$maisonDeCulte) {
        throw $this->createNotFoundException('MaisonDeCulte not found');
    }

    $maisonDeCulte->setNom($nom);
    $maisonDeCulte->setAdresse($adresse);
    $maisonDeCulte->setResponsable($responsable);

    // Additional logic specific to MaisonDeCulte entity

    $entityManager->flush();

    return $this->json(['message' => 'Edit Successful']);
}


    #[Route('/delete/{id}', name: 'app_maison_de_culte_delete', methods: ['POST'])]
    public function delete(Request $request, MaisonDeCulte $maisonDeCulte, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maisonDeCulte->getId(), $request->request->get('_token'))) {
            $entityManager->remove($maisonDeCulte);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_maison_de_culte_index', [], Response::HTTP_SEE_OTHER);
    }
}
