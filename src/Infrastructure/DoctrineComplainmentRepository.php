<?php
namespace App\Infrastructure;

use App\Application\ComplainmentRepository;
use App\Domain\Entity\Complainment;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\UuidInterface;

class DoctrineComplainmentRepository implements ComplainmentRepository
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Complainment::class);
    }

    public function add(Complainment $complainment): void
    {
        $this->entityManager->persist($complainment);
    }

    public function get(UuidInterface $complainmentId): Complainment
    {
        return $this->repository->find($complainmentId);
    }

    public function save(Complainment $complainment): void
    {
        $this->entityManager->merge($complainment);
    }

    public function __destruct()
    {
        $this->entityManager->flush();
    }
}
