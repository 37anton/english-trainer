<?php

namespace App\Controller;

use App\Form\TraductionFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PlayController extends AbstractController
{
    #[Route('/play', name: 'play')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(TraductionFormType::class);

        $form->handleRequest($request);
        return $this->render('play/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
