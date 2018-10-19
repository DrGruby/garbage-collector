<?php
namespace App\Domain\Events;

class ComplainmentConfirmed
{
    const STATUS_CONFIRMED = 'confirmed';
    private $complainmentCloseTime;
    private $confirmationMessage;

    public function __construct(
        string $confirmationMessage
    ) {
        $this->complainmentCloseTime = new \DateTimeImmutable();
        $this->confirmationMessage = $confirmationMessage;
        $this->status = self::STATUS_CONFIRMED;
    }

    public function complainmentCloseTime(): DateTimeImmutable
    {
        $this->complainmentCloseTime;
    }

    public function confirmationMessage(): string
    {
        $this->confirmationMessage;
    }

    public function status(): string
    {
        $this->status;
    }
}
