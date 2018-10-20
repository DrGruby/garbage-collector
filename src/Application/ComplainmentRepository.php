<?php
namespace App\Application;

use App\Domain\Entity\Complainment;
use Ramsey\Uuid\UuidInterface;

interface ComplainmentRepository
{
    public function add(Complainment $complainment): void;

    public function get(UuidInterface $complainmentId): Complainment;

    // implement collection?
    // public function getByStatus(string $status): array;

    // public function getByComplainmentType(string $complainmentType): array;

    public function save(Complainment $complainment): void;
}
