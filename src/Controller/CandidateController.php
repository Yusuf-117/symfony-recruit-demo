<?php

namespace App\Controller;

use App\Repository\CandidateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CandidateController extends AbstractController
{
    #[Route('/candidates', name: 'candidates')]
    public function index(CandidateRepository $repo): Response
    {
        return $this->render('candidate/index.html.twig', [
            'candidates' => $repo->findAll(),
        ]);
    }
}
