<?php

namespace App\Domain\Entity;

use Ramsey\Uuid\UuidInterface;

class Lap
{
    private $truckId;
    private $startTime;

    public function __construct(UuidInterface $truckId, \DateTimeImmutable $startTime)
    {
        $this->truckId = $truckId;
        $this->startTime = $startTime;
    }

    public function collectGarbage(): void
    {
    }

    public function truckId(): UuidInterface
    {
        return $this->truckId;
    }

    public function startTime(): \DateTimeImmutable
    {
        return $this->startTime;
    }
}
