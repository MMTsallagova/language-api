<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KanjiCardController extends AbstractController
{
    #[Route('/kanji/card', name: 'app_kanji_card')]
    public function index(): Response
    {
        return $this->render('kanji_card/index.html.twig', [
            'controller_name' => 'KanjiCardController',
        ]);
    }
}
