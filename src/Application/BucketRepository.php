<?php

namespace App\Application;

use App\Domain\Entity\Bucket;
use Ramsey\Uuid\UuidInterface;

interface BucketRepository
{
    public function add(Bucket $bucket): void;

    public function get(UuidInterface $bucketId): Bucket;

    public function getByRFID(string $bucketRFID): ?Bucket;
}
