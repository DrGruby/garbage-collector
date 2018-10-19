<?php

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class GarbagePickup
{
    private $id;
    private $bucketId;
    private $collectionTime;
    private $garbageType;

    public function __construct(UuidInterface $bucketId, \DateTimeImmutable $collectionTime, string $garbageType)
    {
        $this->id = Uuid::uuid4();
        $this->bucketId = $bucketId;
        $this->collectionTime = $collectionTime;
        $this->garbageType = $garbageType;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function bucketId(): UuidInterface
    {
        return $this->bucketId;
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
