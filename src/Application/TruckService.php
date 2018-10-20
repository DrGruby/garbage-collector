<?php

namespace App\Application;

use App\Domain\Entity\GarbagePickup;
use App\Domain\Entity\Lap;
use App\Domain\Entity\Truck;
use App\Domain\Events\TruckCollectedPayload;
use App\Domain\Events\TruckDeparted;
use App\Domain\Events\TruckUnloaded;

class TruckService
{
    private $truckRepository;
    private $lapRepository;
    private $garbagePickupRepository;
    private $bucketRepository;

    public function __construct(
        TruckRepository $truckRepository,
        LapRepository $lapRepository,
        GarbagePickupRepository $garbagePickupRepository,
        BucketRepository $bucketRepository
    )
    {
        $this->truckRepository = $truckRepository;
        $this->lapRepository = $lapRepository;
        $this->garbagePickupRepository = $garbagePickupRepository;
        $this->bucketRepository = $bucketRepository;
    }

    public function newLap(TruckDeparted $event)
    {
        $truck = $this->truckRepository->getByPlate($event->truckPlatesId());
        $newLap = new Lap($truck->id(), $event->departureTime());
        $this->lapRepository->save($newLap);
    }

    public function garbageCollected(TruckCollectedPayload $event)
    {
        $bucket = $this->bucketRepository->getByRFID($event->bucketRfid());
        $garbagePickup = new GarbagePickup($bucket->id(), $event->collectionTime(), $event->garbageType());

        $truck = $this->truckRepository->getByPlate($event->truckPlatesId());

        $lap = $this->lapRepository->getActiveLapForTruckId($truck->id());

        if ($lap === null) {
            $lap = new Lap($truck->id(), $event->collectionTime());
            $this->lapRepository->save($lap);
        }

        $lap->collectGarbage($garbagePickup->id());

        $this->garbagePickupRepository->add($garbagePickup);
    }

    public function truckUnloaded(TruckUnloaded $event)
    {
        $truck = $this->truckRepository->getByPlate($event->truckPlatesId());
        if ($truck === null) return;
        $lap = $this->lapRepository->getActiveLapForTruckId($truck->id());
        if ($lap === null) return;
        $lap->finish($event->garbageWeight(), $event->truckArriveTime(), $event->district(), $event->garbageType());
        $this->lapRepository->save($lap);
    }
}
