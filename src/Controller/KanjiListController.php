<?php

namespace App\Controller;

use App\Entity\GrammarList;
use App\Entity\KanjiList;
use App\Entity\Vocabulary;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KanjiListController extends AbstractController
{
    /**
     * @Route("/api/kanji_list/create", name="kanjiList_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);
        $kanjiList = new KanjiList();
        $kanjiList->setTitle($params['title']);
        $kanjiList->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($kanjiList);
        $entityManager->flush();

        return $this->json(['status' => 'Kanji list created!', 'id' => $kanjiList->getId()]);
    }

    /**
     * @Route("/api/kanji_list/update", name="kanjiList_update", methods={"POST"})
     */
    public function update(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);

        $kanjiList = $entityManager->getRepository(KanjiList::class)->find($params['id']);

        if (!$kanjiList) {
            return $this->json(['status' => "Kanji list with id: {$params['id']} not exist!"]);
        }

        $kanjiList->setTitle($params['title'] ?? $kanjiList->getTitle());
        $kanjiList->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($kanjiList);
        $entityManager->flush();

        return $this->json(['status' => 'Kanji list updated!', 'id' => $kanjiList->getId()]);
    }

    /**
     * @Route("/api/kanji_list/get/{id}", name="kanjiList_find", methods={"GET"})
     */
    public function getKanjiListById(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $kanjiList = $entityManager->getRepository(KanjiList::class)->find($id);

        return $this->json([
            'id' => $kanjiList->getId(),
            'title' => $kanjiList->getTitle(),
            'created_at' => $kanjiList->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $kanjiList->getUpdatedAt() ? $kanjiList->getUpdatedAt()->format('Y-m-d H:i:s') : null,
            'kanjiCard' => []
        ]);
    }

    /**
     * @Route("/api/kanji_list", name="kanji_list", methods={"GET"})
     */
    public function getKanjiList_List(EntityManagerInterface $entityManager): JsonResponse
    {
        $list = $entityManager->getRepository(KanjiList::class)->findAll();

        $result = [];

        foreach ($list as $kanjiList) {
            /** @var KanjiList $kanjiList */
            $result[] = [
                'id' => $kanjiList->getId(),
                'title' => $kanjiList->getTitle(),
                'created_at' => $kanjiList->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $kanjiList->getUpdatedAt() ? $kanjiList->getUpdatedAt()->format('Y-m-d H:i:s') : null,
            ];
        }

        return $this->json($result);
    }
}
