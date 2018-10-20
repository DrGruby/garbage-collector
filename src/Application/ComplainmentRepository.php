<?php
namespace App\Application;

use App\Domain\Entity\Complainment;
use Ramsey\Uuid\UuidInterface;

interface ComplainmentRepository
{
    public function add(Complainment $complainment): void;

    public function get(UuidInterface $complainmentId): Complainment;

    public function save(Complainment $complainment): void;
}
