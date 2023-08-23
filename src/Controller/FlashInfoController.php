<?php

namespace App\Controller;

use App\Entity\FlashInfo;
use App\Form\FlashInfoType;
use App\Repository\FlashInfoRepository;
use App\Repository\MaisonDeCulteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/flash/info')]
class FlashInfoController extends AbstractController
{
    #[Route('/', name: 'app_flash_info_index', methods: ['GET'])]
    public function index(FlashInfoRepository $flashInfoRepository): Response
    {
        return $this->render('flash_info/index.html.twig', [
            'flash_infos' => $flashInfoRepository->findAll(),
        ]);
    }

    #[Route('/new/{masiondeculteid}', name: 'app_flash_info_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $masiondeculteid, MaisonDeCulteRepository $maisonDeCulteRepository): Response
    {
        $maisonDeCulte = $maisonDeCulteRepository->find($masiondeculteid);

        if (!$maisonDeCulte) {
            throw $this->createNotFoundException('User not found');
        }

        $flashInfo = new FlashInfo();
        $flashInfo->setMaisonDeCulte($maisonDeCulte); // Set the user retrieved from MaisonDeCulteRepository

        $form = $this->createForm(FlashInfoType::class, $flashInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($flashInfo);
            $entityManager->flush();

            return $this->redirectToRoute('app_flash_info_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('flash_info/new.html.twig', [
            'flash_info' => $flashInfo,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_flash_info_show', methods: ['GET'])]
    public function show(FlashInfo $flashInfo): Response
    {
        return $this->render('flash_info/show.html.twig', [
            'flash_info' => $flashInfo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_flash_info_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FlashInfo $flashInfo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FlashInfoType::class, $flashInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_flash_info_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('flash_info/edit.html.twig', [
            'flash_info' => $flashInfo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_flash_info_delete', methods: ['POST'])]
    public function delete(Request $request, FlashInfo $flashInfo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$flashInfo->getId(), $request->request->get('_token'))) {
            $entityManager->remove($flashInfo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_flash_info_index', [], Response::HTTP_SEE_OTHER);
    }
}
