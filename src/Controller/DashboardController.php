<?php

namespace App\Controller;

use App\Repository\CandidateRepository;
use App\Repository\VacancyRepository;
use App\Repository\ApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(
        CandidateRepository $cRepo,
        VacancyRepository $vRepo,
        ApplicationRepository $aRepo
    ): Response {
        return $this->render('dashboard/index.html.twig', [
            'totalCandidates' => $cRepo->count([]),
            'totalVacancies' => $vRepo->count([]),
            'interviews' => $aRepo->count(['stage' => 'interview']),
            'offers' => $aRepo->count(['stage' => 'offer']),
        ]);
    }
}
