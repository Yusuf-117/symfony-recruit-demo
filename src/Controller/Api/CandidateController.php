<?php

namespace App\Controller\Api;

use App\Entity\Candidate;
use App\Repository\CandidateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CandidateController extends AbstractController
{
    #[Route('/api/candidates', methods: ['GET'])]
    public function index(CandidateRepository $repo): JsonResponse
    {
        $data = array_map(fn($c) => [
            'id' => $c->getId(),
            'name' => $c->getName(),
            'email' => $c->getEmail(),
            'phone' => $c->getPhone(),
        ], $repo->findAll());

        return $this->json($data);
    }

    #[Route('/api/candidates/{id}', methods: ['GET'])]
    public function show(Candidate $candidate): JsonResponse
    {
        return $this->json([
            'id' => $candidate->getId(),
            'name' => $candidate->getName(),
            'email' => $candidate->getEmail(),
            'phone' => $candidate->getPhone(),
        ]);
    }

    #[Route('/api/candidates', methods: ['POST'])]
    public function store(Request $req, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($req->getContent(), true);

        $c = new Candidate();
        $c->setName($data['name'] ?? '');
        $c->setEmail($data['email'] ?? '');
        $c->setPhone($data['phone'] ?? null);

        $em->persist($c);
        $em->flush();

        return $this->json(['id' => $c->getId()]);
    }

    #[Route('/api/candidates/{id}', methods: ['PUT'])]
    public function update(Candidate $c, Request $req, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($req->getContent(), true);

        $c->setName($data['name'] ?? $c->getName());
        $c->setEmail($data['email'] ?? $c->getEmail());
        $c->setPhone($data['phone'] ?? $c->getPhone());

        $em->flush();

        return $this->json(['status' => 'updated']);
    }

    #[Route('/api/candidates/{id}', methods: ['DELETE'])]
    public function delete(Candidate $c, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($c);
        $em->flush();

        return $this->json(['status' => 'deleted']);
    }
}
