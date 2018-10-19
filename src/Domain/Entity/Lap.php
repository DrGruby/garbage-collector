<?php

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Lap
{
    const STATUS_ACTIVE = 'active';
    const STATUS_FINISHED = 'finished';
    private $id;
    private $truckId;
    private $startTime;
    private $status;

    public function __construct(UuidInterface $truckId, \DateTimeImmutable $startTime)
    {
        $this->id = Uuid::uuid4();
        $this->truckId = $truckId;
        $this->startTime = $startTime;
        $this->status = self::STATUS_ACTIVE;
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
