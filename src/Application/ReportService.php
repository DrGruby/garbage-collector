<?php

namespace App\Application;

use App\Domain\Entity\Lap;
use App\Infrastructure\ReadModel\LapReport;
use Ramsey\Uuid\UuidInterface;

class ReportService
{
    private $lapRepository;
    private $pickupRepository;
    private $bucketRepository;

    public function __construct(
        LapRepository $lapRepository,
        GarbagePickupRepository $pickupRepository,
        BucketRepository $bucketRepository
    )
    {
        $this->lapRepository = $lapRepository;
        $this->pickupRepository = $pickupRepository;
        $this->bucketRepository = $bucketRepository;
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
        $pickupTypes = [];

        foreach ($lap->pickupIds() as $pickupId) {
            $pickup = $this->pickupRepository->get($pickupId);
            $bucket = $this->bucketRepository->get($pickup->bucketId());
            if (isset($pickupTypes[$bucket->garbageType()])) {
                $pickupTypes[$bucket->garbageType()]++;
            } else {
                $pickupTypes[$bucket->garbageType()] = 1;
            }
        }
        return $pickupTypes;
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
