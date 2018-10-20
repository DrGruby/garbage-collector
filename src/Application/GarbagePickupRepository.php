<?php

namespace App\Application;

use App\Domain\Entity\GarbagePickup;
use Ramsey\Uuid\UuidInterface;

interface GarbagePickupRepository
{
    public function add(GarbagePickup $garbagePickup): void;

    public function get(UuidInterface $pickupId): GarbagePickup;

    /**
     * @param UuidInterface $bucketId
     * @return GarbagePickup[]
     */
    public function findForBucket(UuidInterface $bucketId): array;
}
