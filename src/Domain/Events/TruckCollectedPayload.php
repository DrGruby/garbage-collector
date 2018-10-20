<?php
namespace App\Domain\Events;


use App\Domain\Entity\Position;

class TruckCollectedPayload
{
    private $bucketRfid;
    private $collectionTime;
    private $position;
    private $garbageType;
    private $truckPlatesId;

    public function __construct(
        string $bucketRfid,
        Position $position,
        \DateTimeImmutable $collectionTime,
        string $garbageType,
        string $truckPlatesId
    ) {
        $this->bucketRfid = $bucketRfid;
        $this->collectionTime = $collectionTime;
        $this->position = $position;
        $this->garbageType = $garbageType;
        $this->truckPlatesId = $truckPlatesId;
    }

    public function bucketRfid(): string
    {
        return $this->bucketRfid;
    }

    public function position(): Position
    {
        return $this->position;
    }

    public function collectionTime(): \DateTimeImmutable
    {
        return $this->collectionTime;
    }

    public function garbageType(): string
    {
        return $this->garbageType;
    }

    public function truckPlatesId(): string
    {
        return $this->truckPlatesId;
    }
}
