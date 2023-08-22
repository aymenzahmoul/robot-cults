<?php

namespace App\Controller;

use App\Entity\MaisonDeCulte;
use App\Form\MaisonDeCulteType;
use App\Repository\MaisonDeCulteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/maison/de/culte')]
class MaisonDeCulteController extends AbstractController
{
    #[Route('/', name: 'app_maison_de_culte_index', methods: ['GET'])]
    public function index(MaisonDeCulteRepository $maisonDeCulteRepository): Response
    {
        return $this->render('maison_de_culte/index.html.twig', [
            'maison_de_cultes' => $maisonDeCulteRepository->findAll(),
        ]);
    }

    #[Route('/new/{userid}', name: 'app_maison_de_culte_new', methods: ['GET', 'POST'])]
    public function new(int $userid, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        // Retrieve the User entity based on the provided userid
        $user = $userRepository->find($userid);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $maisonDeCulte = new MaisonDeCulte();
        $maisonDeCulte->setUser($user);
        
        $form = $this->createForm(MaisonDeCulteType::class, $maisonDeCulte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($maisonDeCulte);
            $entityManager->flush();

            return $this->redirectToRoute('app_maison_de_culte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('maison_de_culte/new.html.twig', [
            'maison_de_culte' => $maisonDeCulte,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_maison_de_culte_show', methods: ['GET'])]
    public function show(MaisonDeCulte $maisonDeCulte): Response
    {
        return $this->render('maison_de_culte/show.html.twig', [
            'maison_de_culte' => $maisonDeCulte,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_maison_de_culte_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MaisonDeCulte $maisonDeCulte, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MaisonDeCulteType::class, $maisonDeCulte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_maison_de_culte_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('maison_de_culte/edit.html.twig', [
            'maison_de_culte' => $maisonDeCulte,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_maison_de_culte_delete', methods: ['POST'])]
    public function delete(Request $request, MaisonDeCulte $maisonDeCulte, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$maisonDeCulte->getId(), $request->request->get('_token'))) {
            $entityManager->remove($maisonDeCulte);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_maison_de_culte_index', [], Response::HTTP_SEE_OTHER);
    }
}
