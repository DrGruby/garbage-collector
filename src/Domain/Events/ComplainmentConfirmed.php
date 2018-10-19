<?php
namespace App\Domain\Events;

class ComlainmentConfirmed
{
    const STATUS_REJECTED = 'confirmed';
    private $complainmentCloseTime;
    private $confirmationMessage;

    public function __construct(
        string $confirmationMessage
    ) {
        $this->complainmentCloseTime = new \DateTimeImmutable();
        $this->confirmationMessage = $confirmationMessage;
        $this->status = self::STATUS_REJECTED;
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
