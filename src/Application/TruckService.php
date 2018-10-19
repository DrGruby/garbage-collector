<?php

namespace App\Application;

use App\Domain\Entity\Truck;

class TruckService
{
    private $truckRepository;

    public function __construct(TruckRepository $truckRepository)
    {
        $this->truckRepository = $truckRepository;
    }

    public function createRound() {
        $newTruck = new Truck();
        $this->truckRepository->add($newTruck);
    }
}
