<?php

namespace App\Infrastructure;

use App\Application\EventRepository;
use App\Domain\Entity\Event;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\UuidInterface;

class DoctrineEventRepository implements EventRepository
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Event::class);
    }

    public function add(Event $event): void
    {
        $this->entityManager->persist($event);
    }

    public function getAll(): array
    {
        return $this->repository->findBy([], ['time' => 'ASC']);
    }
    public function __destruct()
    {
        $this->entityManager->flush();
    }
}
