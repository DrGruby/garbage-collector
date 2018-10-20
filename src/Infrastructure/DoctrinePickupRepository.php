<?php

namespace App\Infrastructure;

use App\Application\GarbagePickupRepository;
use App\Domain\Entity\GarbagePickup;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\UuidInterface;

class DoctrinePickupRepository implements GarbagePickupRepository
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(GarbagePickup::class);
    }

    public function add(GarbagePickup $garbagePickup): void
    {
        $this->entityManager->persist($garbagePickup);
        $this->entityManager->flush();
    }

    public function get(UuidInterface $pickupId): GarbagePickup
    {
        return $this->repository->find($pickupId);
    }
}
