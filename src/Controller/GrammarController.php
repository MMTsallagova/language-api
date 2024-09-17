<?php

namespace App\Controller;

use App\Entity\Grammar;
use App\Entity\Word;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrammarController extends AbstractController
{
    /**
     * @Route("/api/grammar/create", name="grammar_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);
        $grammar = new Grammar();
        $grammar->setTitle($params['title']);
        $grammar->setGrammarMeaning($params['grammar_meaning']);
        $grammar->setDescription($params['description']);
        $grammar->setGrammarListId($params['grammarList_id']);
        $grammar->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($grammar);
        $entityManager->flush();

        return $this->json(['status' => 'Grammar created!', 'id' => $grammar->getId()]);
    }

    /**
     * @Route("/api/grammar/update", name="grammar_update", methods={"POST"})
     */
    public function update(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);

        $grammar = $entityManager->getRepository(Grammar::class)->find($params['id']);

        if (!$grammar) {
            return $this->json(['status' => "Grammar with id: {$params['id']} not exist!"]);
        }

        $grammar->setTitle($params['title'] ?? $grammar->getTitle());
        $grammar->setGrammarMeaning($params['grammar_meaning'] ?? $grammar->getGrammarMeaning());
        $grammar->setDescription($params['description']?? $grammar->getDescription());
        $grammar->setGrammarListId($params['grammarList_id']?? $grammar->getGrammarListId());
        $grammar->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($grammar);
        $entityManager->flush();

        return $this->json(['status' => 'Grammar updated!', 'id' => $grammar->getId()]);
    }

    /**
     * @Route("/api/grammar/get/{id}", name="grammar_find", methods={"GET"})
     */
    public function getGrammarById(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $grammar = $entityManager->getRepository(Grammar::class)->find($id);

        return $this->json([
            'id' => $grammar->getId(),
            'title' => $grammar->getTitle(),
            'grammar_meaning' => $grammar->getGrammarMeaning(),
            'description' => $grammar->getDescription(),
            'grammarList_id' => $grammar->getGrammarListId(),
            'created_at' => $grammar->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $grammar->getUpdatedAt() ? $grammar->getUpdatedAt()->format('Y-m-d H:i:s') : null,
        ]);
    }

    /**
     * @Route("/api/grammar", name="grammar_list", methods={"GET"})
     */
    public function getGrammarList(EntityManagerInterface $entityManager): JsonResponse
    {
        $list = $entityManager->getRepository(Grammar::class)->findAll();

        $result = [];

        foreach ($list as $grammar) {
            /** @var Grammar $grammar */
            $result[] = [
                'id' => $grammar->getId(),
                'title' => $grammar->getTitle(),
                'grammar_meaning' => $grammar->getGrammarMeaning(),
                'description' => $grammar->getDescription(),
                'grammarList_id' => $grammar->getGrammarListId(),
                'created_at' => $grammar->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $grammar->getUpdatedAt() ? $grammar->getUpdatedAt()->format('Y-m-d H:i:s') : null,
            ];
        }

        return $this->json($result);
    }

}
