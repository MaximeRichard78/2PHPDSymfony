<?php

namespace App\Controller;

use App\Entity\Upload;
use App\Form\UploadType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    #[Route('/upload', name: 'upload')]
    public function upload(Request $request, FileUploader $fileUploader, ManagerRegistry $doctrine): Response
    {
        $upload = new Upload();
        $form = $this->createForm(UploadType::class, $upload);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()){
            $picture = $form->get('picture')->getData();
        
        if ($picture) {
            try {
                $picture = $fileUploader->upload($picture);
                $upload->setPicture($picture);
            } catch (FileException $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'upload de votre image');
            }
        }
        $em = $doctrine->getManager();
        $em->persist($upload);
        $em->flush();
        $this->addFlash('success', 'Votre image a bien été uploadée');
        return $this->redirectToRoute('about');
    }
    
    return $this->render('upload/upload.html.twig', [
        'form' => $form->createView(),
        'type' => 'create',
        ]);
    }

    public function getLatestUpload(EntityManagerInterface $em)
    {
        $repository = $em->getRepository(Upload::class);
        $latestUpload = $repository->findOneBy([], ['id' => 'DESC']);
    
        return $latestUpload;
    }
    #[Route('/about', name: 'about')]
    public function about(EntityManagerInterface $em): Response
    {
        $latestUpload = $this->getLatestUpload($em);
        return $this->render('about/about.html.twig', [
            'latestUpload' => $latestUpload,
        ]);
    }

   
}

