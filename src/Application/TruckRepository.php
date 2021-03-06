<?php

namespace App\Application;

use App\Domain\Entity\Truck;
use Ramsey\Uuid\UuidInterface;

interface TruckRepository
{
    public function add(Truck $truck): void;

    public function get(UuidInterface $truckId): Truck;

    public function getByPlate(string $truckPlatesId): ?Truck;

    public function getByName(string $truckPlatesId): ?Truck;
}
