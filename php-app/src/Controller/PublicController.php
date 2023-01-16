<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class PublicController extends AbstractController
{
    #[Route('/public', name: 'app_public')]
    public function index(): JsonResponse
    {
        
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PublicController.php',
        ]);
    }
}
