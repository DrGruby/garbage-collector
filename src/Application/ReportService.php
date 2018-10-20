<?php

namespace App\Application;

use App\Domain\Entity\Lap;
use Ramsey\Uuid\UuidInterface;

class ReportService
{
    private $lapRepository;

    public function __construct(LapRepository $lapRepository)
    {
        $this->lapRepository = $lapRepository;
    }

    public function reportForLap(UuidInterface $lapId)
    {
        $lap = $this->lapRepository->get($lapId);
    }

    public function pickedGarbageType(): array
    {
        return [
            'mixed' => 10,
            'dry' => 1
        ];
    }

    public function maxTimeBetweenPickupsInMinutes(Lap $lap): int
    {
    }

    public function fromLastPickupToUnloadInMinutes(Lap $lap): int
    {
    }

    public function lapTimeInMinutes(Lap $lap): int
    {
    }
}
