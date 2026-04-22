<?php

namespace App\Controller;

use App\Repository\ApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PipelineController extends AbstractController
{
    #[Route('/pipeline', name: 'pipeline')]
    public function index(ApplicationRepository $repo): Response
    {
        $apps = $repo->findAll();

        $grouped = [
            'applied' => [],
            'interview' => [],
            'offer' => [],
            'hired' => [],
        ];

        foreach ($apps as $a) {
            $grouped[$a->getStage()][] = $a;
        }

        return $this->render('pipeline/index.html.twig', [
            'grouped' => $grouped,
        ]);
    }
}
