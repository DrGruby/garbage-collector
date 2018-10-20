<?php

namespace App\Application;

use App\Domain\Entity\Lap;
use App\Infrastructure\ReadModel\BucketReport;
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

    public function reportForBucket(UuidInterface $bucketId) {
        $bucket = $this->bucketRepository->get($bucketId);

        $pickups = $this->pickupRepository->findForBucket($bucketId);

        $pickupTimes = [];

        foreach ($pickups as $pickup) {
            $pickupTimes[] = $pickup->collectionTime();
        }

        return new BucketReport(
            $bucket->position(),
            $bucket->rfid(),
            $pickupTimes,
            $this->maxTimeBetweenPickupsInDays($pickups)
        );
    }

    public function maxTimeBetweenPickupsInDays(array $pickups): int
    {
        $days = 0;
        $lastPickup = null;
        foreach ($pickups as $pickup) {
            if ($lastPickup !== null) {
                $daysInterval = (($pickup->collectionTime()->getTimestamp() - $lastPickup->collectionTime()->getTimestamp())/60/60/24);
                if ($daysInterval > $days) $days= (int)$daysInterval;
            }
            $lastPickup = $pickup;
        }

        return $days;
    }

    public function reportForLap(UuidInterface $lapId): LapReport
    {
        $lap = $this->lapRepository->get($lapId);

        return new LapReport(
            $lapId,
            $this->pickedGarbageType($lap),
            $this->maxTimeBetweenPickupsInMinutes($lap),
            $this->fromLastPickupToUnloadInMinutes($lap),
            $this->lapTimeInMinutes($lap),
            $this->eventsForLap($lap)
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
        $maxTime = 0;
        $lastPickup = null;
        foreach ($lap->pickupIds() as $pickupId) {
            $pickup = $this->pickupRepository->get($pickupId);
            if ($lastPickup !== null) {
                $timeInterval = (($pickup->collectionTime()->getTimestamp() - $lastPickup->collectionTime()->getTimestamp())/60);
                if ($timeInterval > $maxTime) $maxTime = (int)$timeInterval;
            }
            $lastPickup = $pickup;
        }

        return $maxTime;
    }

    public function fromLastPickupToUnloadInMinutes(Lap $lap): int
    {
        $minTime = 9999;
        foreach ($lap->pickupIds() as $pickupId) {
            $pickup = $this->pickupRepository->get($pickupId);
            $timeInterval = (($lap->unloadTime()->getTimestamp() - $pickup->collectionTime()->getTimestamp())/60);
                if ($timeInterval < $minTime){
                    $minTime = $timeInterval;
            }
        }
        return $minTime;
    }

    public function lapTimeInMinutes(Lap $lap): int
    {
        return (int)abs(($lap->unloadTime()->getTimestamp() - $lap->startTime()->getTimestamp())/ 60);
    }

    private function eventsForLap(Lap $lap): array
    {
        $events = [];

        $events[] = ['Lap start', $lap->startTime(), ''];
        foreach ($lap->pickupIds() as $pickupId) {
            $pickup = $this->pickupRepository->get($pickupId);
            $bucket = $this->bucketRepository->get($pickup->bucketId());
            $events[] = [
                'Garbage pickup, type:' . $bucket->garbageType(),
                $pickup->collectionTime(),
                (string)$bucket->position()->latitude() . ',' . (string)$bucket->position()->longitude()
            ];
        }
        $events[] = ['Lap end, garbage weight: ' . $lap->garbageWeight(), $lap->unloadTime(), ''];

        return $events;
    }
}
