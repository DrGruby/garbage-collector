<?php

namespace App\Infrastructure\ReadModel;

use App\Domain\Entity\Position;

class BucketReport
{
    private $pickups;
    private $maxDaysBetweenPickups;
    private $position;
    private $rfid;

    public function __construct(
        Position $position,
        string $rfid,
        array $pickups,
        int $maxDaysBetweenPickups
    )
    {
        $this->pickups = $pickups;
        $this->maxDaysBetweenPickups = $maxDaysBetweenPickups;
        $this->position = $position;
        $this->rfid = $rfid;
    }

    public function position(): Position
    {
        return $this->position;
    }

    public function rfid(): string
    {
        return $this->rfid;
    }

    /**
     * @return \DateTimeImmutable[]
     */
    public function pickups(): array
    {
        return $this->pickups;
    }

    public function maxDaysBetweenPickups(): int
    {
        return $this->maxDaysBetweenPickups;
    }
}
