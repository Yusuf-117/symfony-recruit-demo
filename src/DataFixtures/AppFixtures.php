<?php

namespace App\DataFixtures;

use App\Entity\Candidate;
use App\Entity\Vacancy;
use App\Entity\Application;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Candidates
        $candidates = [];
        for ($i = 1; $i <= 10; $i++) {
            $c = new Candidate();
            $c->setName("Candidate $i");
            $c->setEmail("candidate$i@test.com");
            $c->setPhone(null);
            $manager->persist($c);
            $candidates[] = $c;
        }

        // Vacancies
        $vacancies = [];
        for ($i = 1; $i <= 3; $i++) {
            $v = new Vacancy();
            $v->setTitle("Role $i");
            $v->setCompanyName("Company $i");
            $v->setSalaryMin(30000);
            $v->setSalaryMax(50000);
            $v->setLocation("Remote");
            $v->setIsRemote(true);
            $v->setDeadline(new \DateTime());
            $manager->persist($v);
            $vacancies[] = $v;
        }

        $stages = ['applied', 'interview', 'offer'];

        // Applications
        for ($i = 0; $i < 15; $i++) {
            $a = new Application();
            $a->setCandidate($candidates[array_rand($candidates)]);
            $a->setVacancy($vacancies[array_rand($vacancies)]);
            $a->setStage($stages[array_rand($stages)]);
            $a->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($a);
        }

        $manager->flush();
    }
}
