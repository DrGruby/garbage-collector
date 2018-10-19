<?php
namespace App\Domain\Events;

class ComplainmentBeingProcessed
{
    const STATUS_PROCESSING = 'processing';
    private $status;
    private $complainmentProcessingStartTime;

    public function __construct()
    {
        $this->status = self::STATUS_PROCESSING;
        $this->processingStartTime = new \DateTimeImmutable();
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
