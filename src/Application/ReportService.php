<?php

namespace App\Application;

use App\Domain\Entity\Lap;
use App\Infrastructure\ReadModel\LapReport;
use Ramsey\Uuid\UuidInterface;

class ReportService
{
    private $lapRepository;

    public function __construct(LapRepository $lapRepository)
    {
        $this->lapRepository = $lapRepository;
    }

    public function reportForLap(UuidInterface $lapId): LapReport
    {
        $lap = $this->lapRepository->get($lapId);

        return new LapReport(
            $lapId,
            $this->pickedGarbageType($lap),
            $this->maxTimeBetweenPickupsInMinutes($lap),
            $this->fromLastPickupToUnloadInMinutes($lap),
            $this->lapTimeInMinutes($lap)
        );
    }

    public function pickedGarbageType(Lap $lap): array
    {
        return [
            'mixed' => 10,
            'dry' => 1
        ];
    }

    public function maxTimeBetweenPickupsInMinutes(Lap $lap): int
    {
        return 0;
    }

    public function fromLastPickupToUnloadInMinutes(Lap $lap): int
    {
        return 0;
    }

    public function lapTimeInMinutes(Lap $lap): int
    {
        return 0;
    }
}
