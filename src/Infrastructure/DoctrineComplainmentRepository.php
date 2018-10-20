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
        $this->entityManager->flush();
    }

    public function get(UuidInterface $complainmentId): Complainment
    {
        $this->repository->find($complainmentId);
    }

    public function save(Complainment $complainment): void
    {
        $this->entityManager->merge($complainment);
        $this->entityManager->flush();
    }
}
