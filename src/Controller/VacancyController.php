<?php

namespace App\Controller;

use App\Repository\VacancyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VacancyController extends AbstractController
{
    #[Route('/vacancies', name: 'vacancies')]
    public function index(VacancyRepository $repo): Response
    {
        return $this->render('vacancy/index.html.twig', [
            'vacancies' => $repo->findAll(),
        ]);
    }
}
