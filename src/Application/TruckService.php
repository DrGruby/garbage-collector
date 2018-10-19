<?php

namespace App\Application;

use App\Domain\Entity\GarbagePickup;
use App\Domain\Entity\Lap;
use App\Domain\Entity\Truck;
use App\Domain\Events\TruckCollectedPayload;
use App\Domain\Events\TruckDeparted;

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

    public function newLap(TruckDeparted $truckDeparted)
    {
        $truck = $this->truckRepository->getByPlate($truckDeparted->truckPlatesId());
        $newLap = new Lap($truck->id(), $truckDeparted->departureTime());
        $this->lapRepository->add($newLap);
    }

    public function garbageCollected(TruckCollectedPayload $event)
    {
        $bucket = $this->bucketRepository->getByRFID($event->bucketRfid());
        $garbagePickup = new GarbagePickup($bucket->id(), $event->collectionTime(),$event->garbageType());

        $truck = $this->truckRepository->getByPlate($event->truckPlatesId());
        $lap = $this->lapRepository->getActiveLapForTruckId($truck->id());
        $lap->collectGarbage($garbagePickup->id());

        $this->garbagePickupRepository->add($garbagePickup);
    }
}
