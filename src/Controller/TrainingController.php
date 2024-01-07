<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\TrainingType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainingController extends AbstractController
{
    #[Route('/training/create', name: 'training_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $training = new Training();

        $form = $this->createForm(TrainingType::class, $training);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($training);
            $entityManager->flush();
            return $this->redirectToRoute('works');
        }
        return $this->render('training/form.html.twig', [

            'form' => $form->createView(),
            "type" =>"create"
        ]);
    }

    #[Route('/training/update/{id}', name: 'training_update')]
    public function update(Request $request, ManagerRegistry $doctrine, Training $training): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('works');
        }
        return $this->render('training/form.html.twig', [

            'form' => $form->createView(),
            "type" =>"update"
        ]);
    }

    #[Route('/training/delete/{id}', name: 'training_delete')]
    public function delete(ManagerRegistry $doctrine, Training $training): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager = $doctrine->getManager();
        $entityManager->remove($training);
        $entityManager->flush();

        return $this->redirectToRoute('works');
    }

    #[Route('/tri/{sens}', name: 'training_tri', methods: ["POST"], defaults: ["sens" => "asc"])]    
    public function trierParDate($sens, EntityManagerInterface $em)
{
    // Récupérez vos données (c'est un exemple, adaptez-le à votre cas)
    $donnees = $em->getRepository(Training::class)->findAll();
    // Triez les données par date
    usort($donnees, function ($a, $b) use ($sens) {
        if ($sens === 'asc') {
            return $a->getDateDebut() > $b->getDateDebut();
        } else {
            return $a->getDateDebut() < $b->getDateDebut();
        }
    });

    // Renvoyez les données triées
    return $this->render('work/works.html.twig', ['trainings' => $donnees]);
}
}
