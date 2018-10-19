<?php
namespace App\Domain\Events;

class TruckDeparted
{
    private $departureTime;
    private $truckPlatesId;

    public function __construct(
        \DateTimeImmutable $departureTime,
        string $truckPlatesId
    ) {
        $this->departureTime = $departureTime;
        $this->truckPlatesId = $truckPlatesId;
    }

    public function truckPlatesId(): string
    {
        return $this->truckPlatesId;
    }

    public function departureTime(): \DateTimeImmutable
    {
        return $this->departureTime;
    }
}
