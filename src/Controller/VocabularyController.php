<?php

namespace App\Controller;

use App\Entity\Vocabulary;
use App\Entity\Word;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VocabularyController extends AbstractController
{
    /**
     * @Route("/api/vocabulary_list/create", name="list_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);
        $vocabulary = new Vocabulary();
        $vocabulary->setTitle($params['title']);
        $vocabulary->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($vocabulary);
        $entityManager->flush();

        return $this->json(['status' => 'Vocabulary created!', 'id' => $vocabulary->getId()]);
    }

    /**
     * @Route("/api/vocabulary_list/update", name="list_update", methods={"POST"})
     */
    public function update(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);

        $vocabulary = $entityManager->getRepository(Vocabulary::class)->find($params['id']);

        if (!$vocabulary) {
            return $this->json(['status' => "Vocabulary with id: {$params['id']} not exist!"]);
        }

        $vocabulary->setTitle($params['title'] ?? $vocabulary->getTitle());
        $vocabulary->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($vocabulary);
        $entityManager->flush();

        return $this->json(['status' => 'Vocabulary updated!', 'id' => $vocabulary->getId()]);
    }

    /**
     * @Route("/api/vocabulary_list/get/{id}", name="list_find", methods={"GET"})
     */
    public function getVocabularyById(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $vocabulary = $entityManager->getRepository(Vocabulary::class)->find($id);

        return $this->json([
            'id' => $vocabulary->getId(),
            'title' => $vocabulary->getTitle(),
            'created_at' => $vocabulary->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $vocabulary->getUpdatedAt() ? $vocabulary->getUpdatedAt()->format('Y-m-d H:i:s') : null,
            'words' => []
        ]);
    }

    /**
     * @Route("/api/vocabulary_list", name="vocabulary_list", methods={"GET"})
     */
    public function getVocabularyList(EntityManagerInterface $entityManager): JsonResponse
    {
        $list = $entityManager->getRepository(Vocabulary::class)->findAll();

        $result = [];

        foreach ($list as $vocabulary) {
            /** @var Vocabulary $vocabulary */
            $result[] = [
                'id' => $vocabulary->getId(),
                'title' => $vocabulary->getTitle(),
                'created_at' => $vocabulary->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $vocabulary->getUpdatedAt() ? $vocabulary->getUpdatedAt()->format('Y-m-d H:i:s') : null,
            ];
        }

        return $this->json($result);
    }
}
