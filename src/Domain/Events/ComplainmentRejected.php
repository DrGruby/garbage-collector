<?php
namespace App\Domain\Events;

class ComplainmentRejected
{
    const STATUS_REJECTED = 'rejected';
    private $complainmentCloseTime;
    private $rejectionMessage;
    private $status;

    public function __construct(
        string $rejectionMessage
    ) {
        $this->complainmentCloseTime = new \DateTimeImmutable();
        $this->rejectionMessage = $rejectionMessage;
        $this->status = self::STATUS_REJECTED;
    }

    public function complainmentCloseTime(): DateTimeImmutable
    {
        $this->complainmentCloseTime;
    }

    public function rejectionMessage(): string
    {
        $this->rejectionMessage;
    }

    public function status(): string
    {
        $this->status;
    }
}
