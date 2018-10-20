<?php

namespace App\Application;

use App\Domain\Entity\Event;
use Ramsey\Uuid\UuidInterface;

interface EventRepository
{
    public function add(Event $event): void;

    /**
     * @return Event[]
     */
    public function getAll(): array;
}
