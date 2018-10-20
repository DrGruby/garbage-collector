<?php

namespace App\Application;

use App\Domain\Entity\Lap;
use Ramsey\Uuid\UuidInterface;

interface LapRepository
{
    public function save(Lap $lap): void;

    public function get(UuidInterface $lapId): Lap;

    public function getActiveLapForTruckId(UuidInterface $truckId): Lap;
}
