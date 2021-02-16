<?php

namespace App\Controller;

use App\Service\NewsletterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    public function __construct(protected NewsletterService $newsletterService){}

    #[Route('/newsletter', name: 'newsletter', methods: ['POST'])]
    public function index(): Response
    {
        return $this->json([$this->newsletterService->signIn($_POST['email'])]);
    }
}
