<?php

namespace App\Infrastructure;

use App\Application\TruckRepository;
use App\Domain\Entity\Truck;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\UuidInterface;

class DoctrineTruckRepository implements TruckRepository
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Truck::class);
    }

    public function add(Truck $truck): void
    {
        $this->entityManager->persist($truck);
        $this->entityManager->flush();
    }

    public function get(UuidInterface $truckId): Truck
    {
        $this->repository->find($truckId);
    }

    public function getByPlate(string $truckPlatesId): ?Truck
    {
        return $this->repository->findOneBy(['plates' => $truckPlatesId]);
    }

    public function getByName(string $truckName): ?Truck
    {
        return $this->repository->findOneBy(['name' => $truckName]);
    }

    public function __destruct()
    {
        $this->entityManager->flush();
    }
}
