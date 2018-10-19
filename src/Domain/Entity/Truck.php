<?php
namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Truck
{
    private $id;
    private $name;
    private $plates;

    public function __construct(string $name, string $plates)
    {
        $this->id = Uuid::uuid4();
        $this->name = $name;
        $this->plates = $plates;
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function plates(): string
    {
        return $this->plates;
    }
}
