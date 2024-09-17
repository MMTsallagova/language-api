<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrammarListController extends AbstractController
{
    #[Route('/grammar/list', name: 'app_grammar_list')]
    public function index(): Response
    {
        return $this->render('grammar_list/index.html.twig', [
            'controller_name' => 'GrammarListController',
        ]);
    }
}
