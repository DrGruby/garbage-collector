<?php

namespace App\Infrastructure;

use App\Application\LapRepository;
use App\Domain\Entity\Lap;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\UuidInterface;

class DoctrineLapRepository implements LapRepository
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Lap::class);
    }

    public function save(Lap $lap): void
    {
        $this->entityManager->merge($lap);
        $this->entityManager->flush();
    }

    public function get(UuidInterface $lapId): Lap
    {
        return $this->repository->find($lapId);
    }

    public function getAll(): Array
    {
        return $this->repository->find(['status' => Lap::STATUS_FINISHED ]);
    }

    public function getActiveLapForTruckId(UuidInterface $truckId): ?Lap
    {
        return $this->repository->findOneBy(['status' => Lap::STATUS_ACTIVE, 'truckId' => $truckId]);
    }

    public function __destruct()
    {
        $this->entityManager->flush();
    }
}
