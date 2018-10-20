<?php
namespace App\Domain\Events;

use Ramsey\Uuid\UuidInterface;

class ComplainmentRejected
{
    const STATUS_REJECTED = 'rejected';
    private $id;
    private $complainmentCloseTime;
    private $rejectionMessage;
    private $status;

    public function __construct(
        UuidInterface $id,
        string $rejectionMessage
    ) {
        $this->id = $id;
        $this->complainmentCloseTime = new \DateTimeImmutable();
        $this->rejectionMessage = $rejectionMessage;
        $this->status = self::STATUS_REJECTED;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function complainmentCloseTime(): DateTimeImmutable
    {
        return $this->complainmentCloseTime;
    }

    public function rejectionMessage(): string
    {
        return $this->rejectionMessage;
    }

    public function status(): string
    {
        return $this->status;
    }
}
