<?php
namespace App\Domain\Events;

use App\Domain\Entity\Position;

class ComplainmentMade
{
    const STATUS_NEW = 'new';
    private $description;
    private $position;
    private $complainmentType;
    private $complainmentSubmitTime;
    private $status;
    private $submitter;

    public function __construct(
        string $description,
        Position $position,
        string $complainmentType,
        string $submitter
    ) {
        $this->description = $description;
        $this->position = $position;
        $this->complainmentType = $complainmentType;
        $this->complainmentSubmitTime = new \DateTimeImmutable();
        $this->status = self::STATUS_NEW;
        $this->submitter = $submitter;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function position(): Position
    {
        return $this->position;
    }

    public function complainmentType(): string
    {
        return $this->complainmentType;
    }

    public function complainmentSubmitTime(): \DateTimeImmutable
    {
        return $this->complainmentSubmitTime;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function submitter(): string
    {
        return $this->submitter;
    }
}
