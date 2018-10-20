<?php

namespace App\Infrastructure\ReadModel;

class BucketReport
{
    private $pickups;
    private $maxDaysBetweenPickups;

    public function __construct(
        array $pickups,
        int $maxDaysBetweenPickups
    )
    {
        $this->pickups = $pickups;
        $this->maxDaysBetweenPickups = $maxDaysBetweenPickups;
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
