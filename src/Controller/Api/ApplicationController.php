<?php

namespace App\Controller\Api;

use App\Entity\Application;
use App\Entity\Candidate;
use App\Entity\Vacancy;
use App\Repository\ApplicationRepository;
use App\Service\ApplicationStageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ApplicationController extends AbstractController
{
    #[Route('/api/applications', methods: ['GET'])]
    public function index(ApplicationRepository $repo): JsonResponse
    {
        $data = array_map(fn($a) => [
            'id' => $a->getId(),
            'candidate' => $a->getCandidate()?->getName(),
            'vacancy' => $a->getVacancy()?->getTitle(),
            'stage' => $a->getStage(),
        ], $repo->findAll());

        return $this->json($data);
    }

    #[Route('/api/applications', methods: ['POST'])]
    public function store(Request $req, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($req->getContent(), true);

        $candidate = $em->getRepository(Candidate::class)->find($data['candidate_id']);
        $vacancy = $em->getRepository(Vacancy::class)->find($data['vacancy_id']);

        $a = new Application();
        $a->setCandidate($candidate);
        $a->setVacancy($vacancy);
        $a->setStage('applied');
        $a->setCreatedAt(new \DateTimeImmutable());

        $em->persist($a);
        $em->flush();

        return $this->json(['id' => $a->getId()]);
    }

    #[Route('/api/applications/{id}/stage', methods: ['PATCH'])]
    public function nextStage(
        Application $a,
        ApplicationStageService $service,
        EntityManagerInterface $em
    ): JsonResponse {
        $service->moveToNextStage($a);
        $em->flush();

        return $this->json(['stage' => $a->getStage()]);
    }

    #[Route('/api/applications/{id}', methods: ['DELETE'])]
    public function delete(Application $a, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($a);
        $em->flush();

        return $this->json(['status' => 'deleted']);
    }
}
