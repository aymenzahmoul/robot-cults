<?php

namespace App\Controller;

use App\Entity\FlashInfo;
use App\Form\FlashInfoType;
use App\Repository\FlashInfoRepository;
use App\Repository\MaisonDeCulteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class FlashInfoController extends AbstractController
{
    #[Route('/', name: 'app_flash_info_index', methods: ['GET'])]
    public function index(FlashInfoRepository $flashInfoRepository): Response
    {
        return $this->render('flash_info/index.html.twig', [
            'flash_infos' => $flashInfoRepository->findAll(),
        ]);
    }

    #[Route('/flashinfo/create/{maisondeculteid}', name: 'flashinfo_create', methods: ['POST'])]
    public function create(int $maisondeculteid, ManagerRegistry $doctrine, Request $request, MaisonDeCulteRepository $maisonDeCulteRepository): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $em = $doctrine->getManager();
        $decoded = json_decode($request->getContent());
        $titre = $decoded->titre;
        $description = $decoded->description;
        $dateDebut = new \DateTime($decoded->dateDebut);
        $dateFin = new \DateTime($decoded->dateFin);
        // Récupérer la MaisonDeCulte en fonction de l'ID fourni dans maisondeculteid
        $maisonDeCulte = $maisonDeCulteRepository->find($maisondeculteid);
    
        if (!$maisonDeCulte) {
            throw $this->createNotFoundException('Maison de culte non trouvée');
        }
    
        $em->persist($maisonDeCulte);
        $em->flush();
    
        $flashinfo = new Flashinfo();
        $flashinfo->setMaisonDeCulte($maisonDeCulte);
        $flashinfo->setTitre($titre);
        $flashinfo->setDescription($description);
        $flashinfo->setDateDebut($dateDebut);
        $flashinfo->setDateFin($dateFin);
        // Logique supplémentaire spécifique à l'entité Flashinfo
    
        $entityManager->persist($flashinfo);
        $entityManager->flush();
    
        $em->flush();
    
        return $this->json(['message' => 'Succès']);
    }
    
    
    #[Route('/{id}', name: 'app_flash_info_show', methods: ['GET'])]
    public function show(FlashInfo $flashInfo): Response
    {
        return $this->render('flash_info/show.html.twig', [
            'flash_info' => $flashInfo,
        ]);
    }
    #[Route('/flashinfo/{id}', name: 'flashinfo_read', methods: ['GET'])]
public function read(int $id, FlashinfoRepository $flashinfoRepository): JsonResponse
{
    $flashinfo = $flashinfoRepository->find($id);

    if (!$flashinfo) {
        throw $this->createNotFoundException('Flashinfo non trouvé');
    }

    // Vous pouvez personnaliser la réponse JSON pour inclure les détails du flashinfo
    return $this->json([
        'id' => $flashinfo->getId(),
        'titre' => $flashinfo->getTitre(),
        'description' => $flashinfo->getDescription(),
        'dateDebut' => $flashinfo->getDateDebut(),
        'dateFin' => $flashinfo->getDateFin(),
        // Autres champs du flashinfo
    ]);
}


#[Route('/flashinfo/update/{id}', name: 'flashinfo_update', methods: ['PUT'])]
public function update(int $id, Request $request, FlashinfoRepository $flashinfoRepository, EntityManagerInterface $entityManager): JsonResponse
{
    $decoded = json_decode($request->getContent());

    $flashinfo = $flashinfoRepository->find($id);

    if (!$flashinfo) {
        throw $this->createNotFoundException('Flashinfo non trouvé');
    }

    $flashinfo->setTitre($decoded->titre);
    $flashinfo->setDescription($decoded->description);
    $flashinfo->setDateDebut(new \DateTime($decoded->dateDebut));
    $flashinfo->setDateFin(new \DateTime($decoded->dateFin));

    // Autres mises à jour nécessaires

    $entityManager->flush();

    return $this->json(['message' => 'Flashinfo mis à jour avec succès']);
}


#[Route('/flashinfo/delete/{id}', name: 'flashinfo_delete', methods: ['DELETE'])]
public function delete(int $id, FlashinfoRepository $flashinfoRepository, EntityManagerInterface $entityManager): JsonResponse
{
    $flashinfo = $flashinfoRepository->find($id);

    if (!$flashinfo) {
        throw $this->createNotFoundException('Flashinfo non trouvé');
    }

    // Supprimer le flashinfo de la base de données
    $entityManager->remove($flashinfo);
    $entityManager->flush();

    return $this->json(['message' => 'Flashinfo supprimé avec succès']);
}
}
