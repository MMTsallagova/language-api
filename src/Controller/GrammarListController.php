<?php

namespace App\Controller;

use App\Entity\GrammarList;
use App\Entity\Vocabulary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrammarListController extends AbstractController
{
    /**
     * @Route("/api/grammar_list/create", name="grammarList_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);
        $grammarList = new GrammarList();
        $grammarList->setTitle($params['title']);
        $grammarList->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($grammarList);
        $entityManager->flush();

        return $this->json(['status' => 'Grammar list created!', 'id' => $grammarList->getId()]);
    }

    /**
     * @Route("/api/grammar_list/update", name="grammarList_update", methods={"POST"})
     */
    public function update(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);

        $grammarList = $entityManager->getRepository(GrammarList::class)->find($params['id']);

        if (!$grammarList) {
            return $this->json(['status' => "GrammarList with id: {$params['id']} not exist!"]);
        }

        $grammarList->setTitle($params['title'] ?? $grammarList->getTitle());
        $grammarList->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($grammarList);
        $entityManager->flush();

        return $this->json(['status' => 'GrammarList updated!', 'id' => $grammarList->getId()]);
    }

    /**
     * @Route("/api/grammar_list/get/{id}", name="grammarList_find", methods={"GET"})
     */
    public function getGrammarListById(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $grammarList = $entityManager->getRepository(GrammarList::class)->find($id);

        return $this->json([
            'id' => $grammarList->getId(),
            'title' => $grammarList->getTitle(),
            'created_at' => $grammarList->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $grammarList->getUpdatedAt() ? $grammarList->getUpdatedAt()->format('Y-m-d H:i:s') : null,
            'grammar' => []
        ]);
    }

    /**
     * @Route("/api/grammar_list", name="grammarList_list", methods={"GET"})
     */
    public function getGrammarList_List(EntityManagerInterface $entityManager): JsonResponse
    {
        $list = $entityManager->getRepository(GrammarList::class)->findAll();

        $result = [];

        foreach ($list as $grammarList) {
            /** @var GrammarList $grammarList */
            $result[] = [
                'id' => $grammarList->getId(),
                'title' => $grammarList->getTitle(),
                'created_at' => $grammarList->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $grammarList->getUpdatedAt() ? $grammarList->getUpdatedAt()->format('Y-m-d H:i:s') : null,
            ];
        }

        return $this->json($result);
    }

}
