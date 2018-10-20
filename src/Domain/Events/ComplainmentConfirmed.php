<?php
namespace App\Domain\Events;

use Ramsey\Uuid\UuidInterface;

class ComplainmentConfirmed
{
    const STATUS_CONFIRMED = 'confirmed';
    private $id;
    private $complainmentCloseTime;
    private $confirmationMessage;

    public function __construct(
        UuidInterface $id,
        string $confirmationMessage
    ) {
        $this->id = $id;
        $this->complainmentCloseTime = new \DateTimeImmutable();
        $this->confirmationMessage = $confirmationMessage;
        $this->status = self::STATUS_CONFIRMED;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function complainmentCloseTime(): \DateTimeImmutable
    {
        return $this->complainmentCloseTime;
    }

    public function confirmationMessage(): string
    {
        return $this->confirmationMessage;
    }

    public function status(): string
    {
        return $this->status;
    }
}
