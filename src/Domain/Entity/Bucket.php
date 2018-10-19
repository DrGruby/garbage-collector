<?php

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;

class Bucket
{
    const GARBAGE_DRY = 'dry';
    const GARBAGE_MIXED = 'mixed';
    const GARBAGE_GLASS = 'glass';
    private $id;
    private $rfid;
    private $garbageType;

    public function __construct(string $rfid, string $garbageType)
    {
        $this->id = Uuid::uuid4();
        $this->rfid = $rfid;
        $this->garbageType = $garbageType;
    }

    public function id(): \Ramsey\Uuid\UuidInterface
    {
        return $this->id;
    }

    public function rfid(): string
    {
        return $this->rfid;
    }

    public function garbageType(): string
    {
        return $this->garbageType;
    }
}
