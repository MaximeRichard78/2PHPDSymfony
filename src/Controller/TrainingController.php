<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\TrainingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainingController extends AbstractController
{
    #[Route('/training/create', name: 'training_create')]
    public function create(Request $request): Response
    {
        $training = new Training();

        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump($training);
        }
        return $this->render('training/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
