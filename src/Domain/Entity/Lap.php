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
    /** @var UuidInterface[] */
    private $pickupIds;
    private $garbageWeight;
    private $unloadTime;
    private $district;
    private $garbageType;

    public function __construct(UuidInterface $truckId, \DateTimeImmutable $startTime)
    {
        $this->id = Uuid::uuid4();
        $this->truckId = $truckId;
        $this->startTime = $startTime;
        $this->status = self::STATUS_ACTIVE;
        $this->pickupIds = [];
    }

    public function collectGarbage(UuidInterface $pickupId): void
    {
        $this->pickupIds[] = $pickupId;
    }

    public function finish(float $garbageWeight, \DateTimeImmutable $unloadTime, int $district, string $garbageType) {
        $this->garbageWeight = $garbageWeight;
        $this->unloadTime = $unloadTime;
        $this->district = $district;
        $this->garbageType = $garbageType;
        $this->status = self::STATUS_FINISHED;

    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function pickupIds(): array
    {
        return $this->pickupIds;
    }

    public function garbageWeight()
    {
        return $this->garbageWeight;
    }

    public function unloadTime()
    {
        return $this->unloadTime;
    }
    public function district()
    {
        return $this->district;
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
