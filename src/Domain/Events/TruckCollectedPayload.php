<?php
namespace App\Domain\Events;

use App\Domain\ValueObject\CollectionPoint;

class TruckCollectedPayload
{
    private $bucketRfid;
    private $collectionTime;
    private $collectionPoint;
    private $garbageType;

    public function __construct(
        string $bucketRfid,
        CollectionPoint $collectionPoint,
        \DateTimeImmutable $collectionTime,
        string $garbageType
    ) {
        $this->bucketRfid = $bucketRfid;
        $this->collectionTime = $collectionTime;
        $this->collectionPoint = $collectionPoint;
        $this->garbageType = $garbageType;
    }

    public function bucketRfid(): string
    {
        return $this->bucketRfid;
    }

    public function collectionPoint(): CollectionPoint
    {
        return $this->collectionPoint;
    }

    public function collectionTime(): \DateTimeImmutable
    {
        return $this->collectionTime;
    }

    public function garbageType(): string
    {
        return $this->garbageType;
    }
}
