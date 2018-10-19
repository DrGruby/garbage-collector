<?php
namespace App\Domain\Events;

class TruckLapFinished
{
    private $finishTime;
    private $truckPlatesId;

    public function __construct(
        \DateTimeImmutable $finishTime,
        string $truckPlatesId
    ) {
        $this->finishTime = $finishTime;
        $this->truckPlatesId = $truckPlatesId;
    }

    public function finishTime(): \DateTimeImmutable
    {
        return $this->finishTime;
    }

    public function truckPlatesId(): string
    {
        return $this->truckPlatesId;
    }
}
