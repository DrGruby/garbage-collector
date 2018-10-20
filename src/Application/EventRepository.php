<?php

namespace App\Application;

use App\Domain\Entity\Event;
use Ramsey\Uuid\UuidInterface;

interface EventRepository
{
    public function add(\DateTimeImmutable $time, string $data): void;

    /**
     * @return Event[]
     */
    public function getAll(): array;
}
