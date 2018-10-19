<?php

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;

class Bucket
{
    const GARBAGE_DRY = 'dry';
    const GARBAGE_MIXED = 'mixed';
    const GARBAGE_GLASS = 'glass';
    const GARBAGE_BIODEGRADABLE = 'biodegradable';
    const GARBAGE_BULKY = 'bulky';
    private $id;
    private $rfid;
    private $garbageType;
    private $position;
    private $district;

    public function __construct(string $rfid, string $garbageType, Position $position, int $district)
    {
        $this->id = Uuid::uuid4();
        $this->rfid = $rfid;
        $this->garbageType = $garbageType;
        $this->position = $position;
        $this->district = $district;
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

    public function position(): Position
    {
        return $this->position;
    }

    public function district(): int
    {
        return $this->district;
    }
}
