<?php
/**
 * Created by PhpStorm.
 * User: karol-mocniak
 * Date: 20.10.18
 * Time: 13:21
 */

namespace App\Infrastructure\ReadModel;

use Ramsey\Uuid\UuidInterface;

class LapReport
{
    private $lapId;
    private $pickedGarbage;
    private $maxTimeBetweenPickups;
    private $timeToUnload;
    private $totalLapTime;

    public function __construct(UuidInterface $lapId, array $pickedGarbage, int $maxTimeBetweenPickups, int $timeToUnload, int $totalLapTime)
    {
        $this->lapId = $lapId;
        $this->pickedGarbage = $pickedGarbage;
        $this->maxTimeBetweenPickups = $maxTimeBetweenPickups;
        $this->timeToUnload = $timeToUnload;
        $this->totalLapTime = $totalLapTime;
    }

    public function lapId(): UuidInterface
    {
        return $this->lapId;
    }

    public function pickedGarbage(): array
    {
        return $this->pickedGarbage;
    }

    public function maxTimeBetweenPickups(): int
    {
        return $this->maxTimeBetweenPickups;
    }

    public function timeToUnload(): int
    {
        return $this->timeToUnload;
    }

    public function totalLapTime(): int
    {
        return $this->totalLapTime;
    }

}
