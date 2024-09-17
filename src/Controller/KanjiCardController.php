<?php

namespace App\Controller;

use App\Entity\KanjiCard;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class KanjiCardController extends AbstractController
{
    /**
     * @Route("/api/kanjiCard/create", name="kanjiCard_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);
        $kanji = new KanjiCard();
        $kanji->setKanji($params['kanji']);
        $kanji->setFurigana($params['furigana']);
        $kanji->setTranslate($params['translate']);
        $kanji->setDescription($params['description']);
        $kanji->setKanjiListId($params['kanjiList_id']);
        $kanji->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($kanji);
        $entityManager->flush();

        return $this->json(['status' => 'kanji card created!', 'id' => $kanji->getId()]);
    }

    /**
     * @Route("/api/kanjiCard/update", name="kanjiCard_update", methods={"POST"})
     */
    public function update(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);

        $kanji = $entityManager->getRepository(KanjiCard::class)->find($params['id']);

        if (!$kanji) {
            return $this->json(['status' => "KanjiCard with id: {$params['id']} not exist!"]);
        }

        $kanji->setKanji($params['kanji'] ?? $kanji->getKanji());
        $kanji->setFurigana($params['furigana']?? $kanji->getFurigana());
        $kanji->setTranslate($params['translate']?? $kanji->getTranslate());
        $kanji->setDescription($params['description']?? $kanji->getDescription());
        $kanji->setKanjiListId($params['kanjiList_id']?? $kanji->getKanjiListId());
        $kanji->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($kanji);
        $entityManager->flush();

        return $this->json(['status' => 'Kanji card updated!', 'id' => $kanji->getId()]);
    }

    /**
     * @Route("/api/kanjiCard/get/{id}", name="kanjiCard_find", methods={"GET"})
     */
    public function getKanjiCardById(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $kanji = $entityManager->getRepository(KanjiCard::class)->find($id);

        return $this->json([
            'id' => $kanji->getId(),
            'kanji' => $kanji->getKanji(),
            'furigana' => $kanji->getFurigana(),
            'translate' => $kanji->getTranslate(),
            'description' => $kanji->getDescription(),
            'kanjiList_id' => $kanji->getKanjiListId(),
            'created_at' => $kanji->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $kanji->getUpdatedAt() ? $kanji->getUpdatedAt()->format('Y-m-d H:i:s') : null,
        ]);
    }

    /**
     * @Route("/api/kanjiCard", name="kanjiCard_list", methods={"GET"})
     */
    public function getKanjiCardList(EntityManagerInterface $entityManager): JsonResponse
    {
        $list = $entityManager->getRepository(KanjiCard::class)->findAll();

        $result = [];

        foreach ($list as $kanji) {
            /** @var KanjiCard $kanji */
            $result[] = [
                'id' => $kanji->getId(),
                'kanji' => $kanji->getKanji(),
                'furigana' => $kanji->getFurigana(),
                'translate' => $kanji->getTranslate(),
                'description' => $kanji->getDescription(),
                'kanjiList_id' => $kanji->getKanjiListId(),
                'created_at' => $kanji->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $kanji->getUpdatedAt() ? $kanji->getUpdatedAt()->format('Y-m-d H:i:s') : null,
            ];
        }

        return $this->json($result);
    }
}
