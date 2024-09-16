<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="app_api", methods={"GET"})
     */
    public function example(Request $request): JsonResponse
    {
        $data = [
            'message' => 'Hello, AP1I!',
            'time' => time(),
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/api/test", name="api_test1", methods={"GET"})
     */
    public function testEndpoint(Request $request): JsonResponse
    {
        $data = [
            'message' => 'This is a test API endpoint',
            'status' => 'success',
            'timestamp' => time()
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route("/api/t", name="api_test", methods={"GET"})
     */
    public function test(Request $request): JsonResponse
    {
        $data = [
            'message' => 'This is ',
            'status' => 'success',
            'timestamp' => time()
        ];

        return new JsonResponse($data);
    }

    /**
     * @Route ("/api/m", name="api_testtest", methods={"GET"})
     */
    public  function testMargoshka(Request $request): JsonResponse
    {
        $data = [
            'message' => 'This is Margoshka',
            'status' => 'hehe',
            'timestamp' => time()
        ];

        return  new JsonResponse($data);
    }


}
