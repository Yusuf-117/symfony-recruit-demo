<?php

namespace App\Controller\Api;

use App\Entity\Vacancy;
use App\Repository\VacancyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class VacancyController extends AbstractController
{
    #[Route('/api/vacancies', methods: ['GET'])]
    public function index(VacancyRepository $repo): JsonResponse
    {
        $data = array_map(fn($v) => [
            'id' => $v->getId(),
            'title' => $v->getTitle(),
            'companyName' => $v->getCompanyName(),
            'salaryMin' => $v->getSalaryMin(),
            'salaryMax' => $v->getSalaryMax(),
            'location' => $v->getLocation(),
            'isRemote' => $v->isRemote(),
            'deadline' => $v->getDeadline()?->format('Y-m-d H:i:s'),
        ], $repo->findAll());

        return $this->json($data);
    }

    #[Route('/api/vacancies/{id}', methods: ['GET'])]
    public function show(Vacancy $v): JsonResponse
    {
        return $this->json([
            'id' => $v->getId(),
            'title' => $v->getTitle(),
            'companyName' => $v->getCompanyName(),
            'salaryMin' => $v->getSalaryMin(),
            'salaryMax' => $v->getSalaryMax(),
            'location' => $v->getLocation(),
            'isRemote' => $v->isRemote(),
            'deadline' => $v->getDeadline()?->format('Y-m-d H:i:s'),
        ]);
    }

    #[Route('/api/vacancies', methods: ['POST'])]
    public function store(Request $req, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($req->getContent(), true);

        $v = new Vacancy();
        $v->setTitle($data['title'] ?? '');
        $v->setCompanyName($data['companyName'] ?? '');
        $v->setSalaryMin($data['salaryMin'] ?? null);
        $v->setSalaryMax($data['salaryMax'] ?? null);
        $v->setLocation($data['location'] ?? null);
        $v->setIsRemote($data['isRemote'] ?? false);

        if (!empty($data['deadline'])) {
            $v->setDeadline(new \DateTime($data['deadline']));
        }

        $em->persist($v);
        $em->flush();

        return $this->json(['id' => $v->getId()]);
    }

    #[Route('/api/vacancies/{id}', methods: ['PUT'])]
    public function update(Vacancy $v, Request $req, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($req->getContent(), true);

        $v->setTitle($data['title'] ?? $v->getTitle());
        $v->setCompanyName($data['companyName'] ?? $v->getCompanyName());
        $v->setSalaryMin($data['salaryMin'] ?? $v->getSalaryMin());
        $v->setSalaryMax($data['salaryMax'] ?? $v->getSalaryMax());
        $v->setLocation($data['location'] ?? $v->getLocation());
        $v->setIsRemote($data['isRemote'] ?? $v->isRemote());

        if (!empty($data['deadline'])) {
            $v->setDeadline(new \DateTime($data['deadline']));
        }

        $em->flush();

        return $this->json(['status' => 'updated']);
    }

    #[Route('/api/vacancies/{id}', methods: ['DELETE'])]
    public function delete(Vacancy $v, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($v);
        $em->flush();

        return $this->json(['status' => 'deleted']);
    }
}
