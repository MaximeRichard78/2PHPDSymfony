<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutthispageController extends AbstractController
{
    #[Route('/aboutthispage', name: 'aboutthispage')]
    public function index(): Response
    {
        return $this->render('aboutthispage/index.html.twig', [
            'controller_name' => 'AboutthispageController',
        ]);
    }
}
