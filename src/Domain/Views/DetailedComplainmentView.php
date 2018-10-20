<?php
namespace App\Domain\Views;

use App\Domain\Entity\Position;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class DetailedComplainmentView
{
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
    private $submitter;

    public function __construct(
        Uuid $id,
        ?\DateTimeImmutable $complainmentCloseTime,
        ?\DateTimeImmutable $complainmentProcessingStartTime,
        \DateTimeImmutable $complainmentSubmitTime,
        string $complainmentType,
        ?string $confirmationMessage,
        string $description,
        Position $position,
        ?string $rejectionMessage,
        string $status,
        string $submitter
    ) {
        $this->id = $id;
        $this->complainmentCloseTime = $complainmentCloseTime;
        $this->complainmentProcessingStartTime = $complainmentProcessingStartTime;
        $this->complainmentSubmitTime = $complainmentSubmitTime;
        $this->complainmentType = $complainmentType;
        $this->confirmationMessage = $confirmationMessage;
        $this->position = $position;
        $this->description = $description;
        $this->rejectionMessage = $rejectionMessage;
        $this->status = $status;
        $this->submitter = $submitter;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function complainmentCloseTime(): ?\DateTimeImmutable
    {
        return $this->complainmentCloseTime;
    }

    public function complainmentProcessingStartTime(): ?\DateTimeImmutable
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

    public function confirmationMessage(): ?string
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

    public function rejectionMessage(): ?string
    {
        return $this->rejectionMessage;
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
