<?php

namespace App\Controller;

use App\Entity\Contribution;
use App\Form\ContributionType;
use App\Repository\ContributionRepository;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ContributionController extends AbstractController
{
    #[Route('/', name: 'app_contribution_index', methods: ['GET'])]
    public function index(ContributionRepository $contributionRepository): Response
    {
        return $this->render('contribution/index.html.twig', [
            'contributions' => $contributionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_contribution_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contribution = new Contribution();
        $form = $this->createForm(ContributionType::class, $contribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contribution);
            $entityManager->flush();

            return $this->redirectToRoute('app_contribution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contribution/new.html.twig', [
            'contribution' => $contribution,
            'form' => $form,
        ]);
    }

    #[Route('/contribution/create/{userid}/{projetid}', name: 'contribution_create', methods: ['POST'])]
    public function createContribution(int $userid, int $projetid, ManagerRegistry $doctrine, Request $request, UserRepository $userRepository, ProjetRepository $projetRepository): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $decoded = json_decode($request->getContent());

        $description = $decoded->description;
        $montant = $decoded->montant;
        $date = new \DateTime($decoded->date);
        $typeContribution = $decoded->typeContribution;

        $user = $userRepository->find($userid);
        $projet = $projetRepository->find($projetid);

        if (!$user || !$projet) {
            throw $this->createNotFoundException('User or Projet not found');
        }

        $contribution = new Contribution();
        $contribution->setUser($user);
        $contribution->setProjet($projet);
        $contribution->setDescription($description);
        $contribution->setMontant($montant);
        $contribution->setDate($date);
        
        $contribution->setTypeContribution($typeContribution);

        // Additional logic specific to Contribution entity

        $entityManager->persist($contribution);
        $entityManager->flush();

        return $this->json(['message' => 'Contribution created successfully']);
    }

    #[Route('/{id}', name: 'app_contribution_show', methods: ['GET'])]
    public function show(Contribution $contribution): Response
    {
        return $this->render('contribution/show.html.twig', [
            'contribution' => $contribution,
        ]);
    }

    #[Route('/contribution/update/{id}', name: 'contribution_update', methods: ['PUT'])]
    public function updateContribution(int $id, Request $request, ContributionRepository $contributionRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $contribution = $contributionRepository->find($id);

        if (!$contribution) {
            throw $this->createNotFoundException('Contribution not found');
        }

        $decoded = json_decode($request->getContent());

        
        $contribution->setDescription($decoded->description);
        $contribution->setMontant($decoded->montant);

      
        $contribution->setDate(new \DateTime($decoded->date));
        $contribution->setTypeContribution($decoded->typeContribution);


        $entityManager->flush();

        return $this->json(['message' => 'Contribution updated successfully']);
    }

    #[Route('/contribution/delete/{id}', name: 'contribution_delete', methods: ['DELETE'])]
    public function deleteContribution(int $id, ContributionRepository $contributionRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        // Retrieve the Contribution to be deleted by ID from the database
        $contribution = $contributionRepository->find($id);
    
        if (!$contribution) {
            throw $this->createNotFoundException('Contribution not found');
        }
    
        // Remove the Contribution entity
        $entityManager->remove($contribution);
        $entityManager->flush();
    
        return $this->json(['message' => 'Contribution deleted successfully']);
    }
    
}
