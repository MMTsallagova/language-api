<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KanjiListController extends AbstractController
{
    #[Route('/kanji/list', name: 'app_kanji_list')]
    public function index(): Response
    {
        return $this->render('kanji_list/index.html.twig', [
            'controller_name' => 'KanjiListController',
        ]);
    }
}
