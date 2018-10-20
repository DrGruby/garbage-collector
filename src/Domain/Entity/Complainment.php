<?php
namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Complainment
{
    const STATUS_NEW = 'new';
    const STATUS_PROCESSING = 'processing';
    const STATUS_REJECTED = 'rejected';
    const STATUS_CONFIRMED = 'confirmed';
    private $complainmentCloseTime;
    private $complainmentProcessingStartTime;
    private $complainmentSubmitTime;
    private $complainmentType;
    private $confirmationMessage;
    private $description;
    private $position;
    private $id;
    private $rejectionMessage;
    private $status;

    public function __construct(
        \DateTimeImmutable $complainmentCloseTime,
        \DateTimeImmutable $complainmentProcessingStartTime,
        \DateTimeImmutable $complainmentSubmitTime,
        string $complainmentType,
        string $confirmationMessage,
        string $description,
        Position $position,
        string $rejectionMessage,
        string $status
    ) {
        $this->id = Uuid::uuid4();
        $this->complainmentCloseTime = $complainmentCloseTime;
        $this->complainmentProcessingStartTime = $complainmentProcessingStartTime;
        $this->complainmentSubmitTime = $complainmentSubmitTime;
        $this->complainmentType = $complainmentType;
        $this->confirmationMessage = $confirmationMessage;
        $this->position = $position;
        $this->description = $description;
        $this->rejectionMessage = $rejectionMessage;
        $this->status = $status;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function complainmentCloseTime(): \DateTimeImmutable
    {
        return $this->complainmentCloseTime;
    }

    public function complainmentProcessingStartTime(): \DateTimeImmutable
    {
        return $this->complainmentProcessingStartTime;
    }

    public function complainmentSubmitTime(): \DateTimeImmutable
    {
        return $this->complainmentSubmitTime;
    }

    public function complainmentType(): string
    {
        return $this->complainmentType;
    }

    public function confirmationMessage(): string
    {
        return $this->confirmationMessage;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function position(): Position
    {
        return $this->position;
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
