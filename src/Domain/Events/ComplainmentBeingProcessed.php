<?php
namespace App\Domain\Events;

use Ramsey\Uuid\UuidInterface;

class ComplainmentBeingProcessed
{
    const STATUS_PROCESSING = 'processing';
    private $id;
    private $status;
    private $complainmentProcessingStartTime;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
        $this->status = self::STATUS_PROCESSING;
        $this->processingStartTime = new \DateTimeImmutable();
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function processingStartTime(): \DateTimeImmutable
    {
        return $this->processingStartTime;
    }
}
