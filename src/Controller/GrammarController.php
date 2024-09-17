<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrammarController extends AbstractController
{
    #[Route('/grammar', name: 'app_grammar')]
    public function index(): Response
    {
        return $this->render('grammar/index.html.twig', [
            'controller_name' => 'GrammarController',
        ]);
    }
}
