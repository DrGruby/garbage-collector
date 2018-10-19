<?php
namespace App\Domain\Events;

class TruckUnloaded
{
    private $district;
    private $garbageType;
    private $garbageWeight;
    private $truckArriveTime;
    private $truckPlatesId;
    
    public function __construct(
        int $district,
        string $garbageType,
        float $garbageWeight,
        \DateTimeImmutable $truckArriveTime,
        string $truckPlatesId
    ) {
        $this->district = $district;
        $this->garbageType = $garbageType;
        $this->garbageWeight = $garbageWeight;
        $this->truckArriveTime = $truckArriveTime;
        $this->truckPlatesId = $truckPlatesId;
    }

    public function district(): int
    {
        return $this->district;
    }

    public function garbageType(): string
    {
        return $this->garbageType;
    }

    public function garbageWeight(): float
    {
        return $this->garbageWeight;
    }

    public function truckArriveTime(): \DateTimeImmutable
    {
        return $this->truckArriveTime;
    }

    public function truckPlatesId(): string
    {
        return $this->truckPlatesId;
    }
}
