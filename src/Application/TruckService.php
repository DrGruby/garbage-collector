<?php

namespace App\Application;

use App\Domain\Entity\Lap;
use App\Domain\Entity\Truck;
use App\Domain\Events\TruckCollectedPayload;
use App\Domain\Events\TruckDeparted;

class TruckService
{
    private $truckRepository;
    private $lapRepository;

    public function __construct(TruckRepository $truckRepository, LapRepository $lapRepository)
    {
        $this->truckRepository = $truckRepository;
        $this->lapRepository = $lapRepository;
    }

    public function newLap(TruckDeparted $truckDeparted)
    {
        $truck = $this->truckRepository->getByPlate($truckDeparted->truckPlatesId());
        $newLap = new Lap($truck->id(), $truckDeparted->departureTime());
        $this->lapRepository->add($newLap);
    }

    public function garbageCollected(TruckCollectedPayload $event)
    {
        $truck = $this->truckRepository->getByPlate($event->truckPlatesId());
        $lap = $this->lapRepository->getActiveLapForTruckId($truck->id());

    }
}
