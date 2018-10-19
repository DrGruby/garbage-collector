<?php

namespace App\Application;

use App\Domain\Entity\Lap;
use App\Domain\Entity\Truck;

class TruckService
{
    private $truckRepository;
    private $lapRepository;

    public function __construct(TruckRepository $truckRepository, LapRepository $lapRepository)
    {
        $this->truckRepository = $truckRepository;
        $this->lapRepository = $lapRepository;
    }

    public function createRound()
    {
        $newLap = new Lap($truckId, $startTime);
        $this->truckRepository->add($newLap);
    }
}
