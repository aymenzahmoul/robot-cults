<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Form\Paiement1Type;
use App\Repository\PaiementRepository;
use App\Repository\ProjetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
#[Route('/paiement')]
class PaiementController extends AbstractController
{
    #[Route('/', name: 'app_paiement_index', methods: ['GET'])]
    public function index(PaiementRepository $paiementRepository): Response
    {
        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiementRepository->findAll(),
        ]);
    }

    #[Route('/new/{userid}/{projetid}', name: 'app_paiement_new', methods: ['GET', 'POST'])]
    public function new(int $userid, int $projetid, Request $request, UserRepository $userRepository, ProjetRepository $projetRepository, EntityManagerInterface $entityManager, Security $security): Response
    {
        // Check if the user has the required role or is anonymous
        if (!$security->isGranted('ROLE_DONATEUR') && !$security->isGranted('ROLE_VISITER')) {
            throw $this->createAccessDeniedException('Access denied');
        }

        $user = $userRepository->find($userid);
        $projet = $projetRepository->find($projetid);

        if (!$user || !$projet) {
            throw $this->createNotFoundException('User or Projet not found');
        }

        $paiement = new Paiement();


        $form = $this->createForm(Paiement1Type::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Paiement1Type::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paiement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($paiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}
