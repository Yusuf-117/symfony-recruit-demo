<?php

namespace App\Controller;

use App\Repository\ApplicationRepository;
use App\Repository\CandidateRepository;
use App\Repository\VacancyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApplicationController extends AbstractController
{
    #[Route('/applications', name: 'applications')]
    public function index(
        ApplicationRepository $aRepo,
        CandidateRepository $cRepo,
        VacancyRepository $vRepo
    ): Response {
        return $this->render('application/index.html.twig', [
            'applications' => $aRepo->findAll(),
            'candidates' => $cRepo->findAll(),
            'vacancies' => $vRepo->findAll(),
        ]);
    }
}
