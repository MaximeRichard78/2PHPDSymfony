<?php

namespace App\Controller;

use App\Entity\Work;
use App\Form\WorkType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkController extends AbstractController
{
    #[Route('/work/create', name: 'work_create')]
    public function create(Request $request): Response
    {
        $work = new Work();

        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($work);
        }
        return $this->render('work/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/works', name: 'works')]
    public function works(): Response
    {
        return $this->render('work/works.html.twig', [
        ]);
    }
}
