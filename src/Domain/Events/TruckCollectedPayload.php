<?php
namespace App\Domain\Events;

use App\Domain\ValueObject\CollectionPoint;

class TruckCollectedPayload
{
    private $bucketRfid;
    private $collectionTime;
    private $collectionPoint;
    private $garbageType;
    private $truckPlatesId;

    public function __construct(
        string $bucketRfid,
        CollectionPoint $collectionPoint,
        \DateTimeImmutable $collectionTime,
        string $garbageType,
        string $truckPlatesId
    ) {
        $this->bucketRfid = $bucketRfid;
        $this->collectionTime = $collectionTime;
        $this->collectionPoint = $collectionPoint;
        $this->garbageType = $garbageType;
        $this->truckPlatesId = $truckPlatesId;
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

    public function truckPlatesId(): string
    {
        return $this->truckPlatesId;
    }
}
