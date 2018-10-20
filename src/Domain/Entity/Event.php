<?php

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;

class Event
{
    private $id;
    private $time;
    private $data;

    public function __construct(\DateTimeImmutable $time, string $data)
    {
        $this->id = Uuid::uuid4();
        $this->time = $time;
        $this->data = $data;
    }

    public function time(): \DateTimeImmutable
    {
        return $this->time;
    }

    public function data(): string
    {
        return $this->data;
    }
}
