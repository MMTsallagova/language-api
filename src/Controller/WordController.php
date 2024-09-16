<?php

namespace App\Controller;

use App\Entity\Text;
use App\Entity\Word;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class WordController extends AbstractController
{
    /**
     * @Route("/api/word/create", name="word_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);
        $word = new Word();
        $word->setWord($params['word']);
        $word->setFurigana($params['furigana']);
        $word->setTranslate($params['translate']);
        $word->setDescription($params['description']);
        $word->setVocabularyId($params['vocabulary_id']);
        $word->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($word);
        $entityManager->flush();

        return $this->json(['status' => 'Word created!', 'id' => $word->getId()]);
    }

    /**
     * @Route("/api/word/update", name="word_update", methods={"POST"})
     */
    public function update(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);

        $word = $entityManager->getRepository(Word::class)->find($params['id']);

        if (!$word) {
            return $this->json(['status' => "Text with id: {$params['id']} not exist!"]);
        }

        $word->setWord($params['word'] ?? $word->getWord());
        $word->setFurigana($params['furigana'] ?? $word->getFurigana());
        $word->setTranslate($params['translate'] ?? $word->getTranslate());
        $word->setDescription($params['description'] ?? $word->getDescription());
        $word->setVocabularyId($params['vocabulary_id'] ?? $word->getVocabularyId());
        $word->setUpdateAt(new \DateTimeImmutable());

        $entityManager->persist($word);
        $entityManager->flush();

        return $this->json(['status' => 'Text updated!', 'id' => $word->getId()]);
    }

    /**
     * @Route("/api/word/get/{id}", name="word_find", methods={"GET"})
     */
    public function getWordById(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $word = $entityManager->getRepository(Word::class)->find($id);

        return $this->json([
            'id' => $word->getId(),
            'word' => $word->getWord(),
            'furigana' => $word->getFurigana(),
            'translate' => $word->getTranslate(),
            'description' => $word->getDescription(),
            'vocabulary_id' => $word->getVocabularyId(),
            'created_at' => $word->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $word->getUpdateAt() ? $word->getUpdateAt()->format('Y-m-d H:i:s') : null,
        ]);
    }

    /**
     * @Route("/api/word", name="word_list", methods={"GET"})
     */
    public function getWordList(EntityManagerInterface $entityManager): JsonResponse
    {
        $list = $entityManager->getRepository(Word::class)->findAll();

        $result = [];

        foreach ($list as $word) {
            /** @var Word $word */
            $result[] = [
                'id' => $word->getId(),
                'word' => $word->getWord(),
                'furigana' => $word->getFurigana(),
                'translate' => $word->getTranslate(),
                'description' => $word->getDescription(),
                'created_at' => $word->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $word->getUpdateAt() ? $word->getUpdateAt()->format('Y-m-d H:i:s') : null,
                'vocabulary_id' => $word->getVocabularyId(),
            ];
        }

        return $this->json($result);
    }

    /**
     * @Route("/api/wordById/{id}", name="word_list_by_vocabulary", methods={"GET"})
     */
    public function getWordListByVId(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $list = $entityManager->getRepository(Word::class)->findBy(['vocabulary_id' => $id]);

        $result = [];

        foreach ($list as $word) {
            /** @var Word $word */
            $result[] = [
                'id' => $word->getId(),
                'word' => $word->getWord(),
                'furigana' => $word->getFurigana(),
                'translate' => $word->getTranslate(),
                'description' => $word->getDescription(),
                'vocabulary_id' => $word->getVocabularyId(),
                'created_at' => $word->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $word->getUpdateAt() ? $word->getUpdateAt()->format('Y-m-d H:i:s') : null,
            ];
        }

        return $this->json($result);
    }
}
