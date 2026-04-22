<?php

namespace App\Service;

use App\Entity\Application;

class ApplicationStageService
{
    private array $stages = [
        'applied',
        'interview',
        'offer',
        'hired',
    ];

    // Move to specified stage
    public function moveToStage(Application $application, string $targetStage): void
    {
        if (!in_array($targetStage, $this->stages, true)) {
            throw new \InvalidArgumentException('Invalid stage');
        }

        $currentIndex = array_search($application->getStage(), $this->stages);
        $targetIndex = array_search($targetStage, $this->stages);

        if ($targetIndex < $currentIndex) {
            throw new \InvalidArgumentException('Cannot move backwards');
        }

        $application->setStage($targetStage);
    }

    // Move to Next stage
    public function moveToNextStage(Application $application): void
    {
        $currentIndex = array_search($application->getStage(), $this->stages);

        if (!isset($this->stages[$currentIndex + 1])) {
            return;
        }

        $application->setStage($this->stages[$currentIndex + 1]);
    }

    public function getStages(): array
    {
        return $this->stages;
    }
}

