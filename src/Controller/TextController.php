<?php

namespace App\Controller;

use App\Entity\Text;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TextController extends AbstractController
{
    /**
     * @Route("/api/text/create", name="text_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);
        $text = new Text();
        $text->setText($params['text']);
        $text->setTitle($params['title']);
        $text->setNote($params['note']);
        $text->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($text);
        $entityManager->flush();

        return $this->json(['status' => 'Text created!', 'id' => $text->getId()]);
    }

    /**
     * @Route("/api/text/update", name="text_update", methods={"POST"})
     */
    public function update(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $params = json_decode($request->getContent(), true);

        $text = $entityManager->getRepository(Text::class)->find($params['id']);

        if (!$text) {
            return $this->json(['status' => "Text with id: {$params['id']} not exist!"]);
        }

        $text->setText($params['text'] ?? $text->getText());
        $text->setTitle($params['title'] ?? $text->getTitle());
        $text->setNote($params['note'] ?? $text->getNote());
        $text->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($text);
        $entityManager->flush();

        return $this->json(['status' => 'Text updated!', 'id' => $text->getId()]);
    }

    /**
     * @Route("/api/text/get/{id}", name="text_find", methods={"GET"})
     */
    public function getTextById(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $text = $entityManager->getRepository(Text::class)->find($id);

        return $this->json([
            'id' => $text->getId(),
            'title' => $text->getTitle(),
            'text' => $text->getText(),
            'note' => $text->getNote(),
            'created_at' => $text->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $text->getUpdatedAt() ? $text->getUpdatedAt()->format('Y-m-d H:i:s') : null,
        ]);
    }

    /**
     * @Route("/api/text", name="text_list", methods={"GET"})
     */
    public function getTextList(EntityManagerInterface $entityManager): JsonResponse
    {
        $list = $entityManager->getRepository(Text::class)->findAll();

        $result = [];

        foreach ($list as $text) {
            /** @var Text $text */
            $result[] = [
                'id' => $text->getId(),
                'title' => $text->getTitle(),
                'text' => $text->getText(),
                'note' => $text->getNote(),
                'created_at' => $text->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $text->getUpdatedAt() ? $text->getUpdatedAt()->format('Y-m-d H:i:s') : null,
            ];
        }

        return $this->json($result);
    }
}
