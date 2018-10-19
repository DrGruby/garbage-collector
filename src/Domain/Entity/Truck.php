<?php
namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Truck
{
    private $id;

    public function __construct()
    {
        $this-> id = Uuid::uuid4();
    }

    public function id(): UuidInterface
    {
        return $this->id;
    }
}
