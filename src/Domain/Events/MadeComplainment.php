<?php
namespace App\Domain\Events;

use App\Domain\ValueObject\Position;

class MadeComplainment
{
    const STATUS_NEW = 'new';
    private $position;
    private $complainmentType;
    private $complainmentTime;
    private $status;

    public function __construct(
        Position $position,
        string $complainmentType
    ) {
        $this->position = $position;
        $this->complainmentType = $complainmentType;
        $this->complainmentTime = new \DateTimeImmutable();
        $this->status = self::STATUS_NEW;
    }

    public function position(): Position
    {
        return $this->position;
    }

    public function complainmentType(): string
    {
        return $this->complainmentType;
    }

    public function complainmentTime(): \DateTimeImmutable
    {
        return $this->complainmentTime;
    }

    public function status(): string
    {
        return $this->status;
    }
}
