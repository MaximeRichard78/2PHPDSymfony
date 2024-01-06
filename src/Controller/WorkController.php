<?php

namespace App\Controller;

use App\Entity\Training;
use App\Entity\Work;
use App\Form\WorkType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkController extends AbstractController
{
    #[Route('/work/create', name: 'work_create')]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $work = new Work();

        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($work);
            $entityManager->flush();
            return $this->redirectToRoute('works');
        }
        return $this->render('work/form.html.twig', [
            'form' => $form->createView(),
            "type" =>"create"

        ]);
    }
    #[Route('/work/update/{id}', name: 'work_update')]
    public function update(Request $request, ManagerRegistry $doctrine, Work $work): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();
            return $this->redirectToRoute('works');
        }
        return $this->render('work/form.html.twig', [

            'form' => $form->createView(),
            "type" =>"update"

        ]);
    }
    #[Route('/work/delete/{id}', name: 'work_delete')]
    public function delete(ManagerRegistry $doctrine, Work $work): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager();
        $entityManager->remove($work);
        $entityManager->flush();

        return $this->redirectToRoute('works');
    }

    #[Route('/works', name: 'works')]
    public function works(ManagerRegistry $doctrine): Response
    {
        $workRepository = $doctrine->getRepository(Work::class);
        $works = $workRepository->findAll();

        $trainingRepository = $doctrine->getRepository(Training::class);
        $trainings = $trainingRepository->findAll();

        return $this->render('work/works.html.twig', ["works" =>  $works, "trainings" =>  $trainings]);
    }

    #[Route('/tri/{sens}', name: 'works_tri', methods: ["POST"], defaults: ["sens" => "asc"])]    
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
    return $this->render('training/index.html.twig', ['trainings' => $donnees]);
}
}
