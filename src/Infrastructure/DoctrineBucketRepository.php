<?php

namespace App\Infrastructure;

use App\Application\BucketRepository;
use App\Domain\Entity\Bucket;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\UuidInterface;

class DoctrineBucketRepository implements BucketRepository
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Bucket::class);
    }

    public function add(Bucket $bucket): void
    {
        $this->entityManager->persist($bucket);
    }

    public function get(UuidInterface $bucketId): Bucket
    {
        return $this->repository->find($bucketId);
    }

    public function getAll(): array
    {
        return $this->repository->findBy([]);
    }

    public function getByRFID(string $bucketRFID): ?Bucket
    {
        return $this->repository->findOneBy(['rfid' => $bucketRFID]);
    }

    public function __destruct()
    {
        $this->entityManager->flush();
    }
}
