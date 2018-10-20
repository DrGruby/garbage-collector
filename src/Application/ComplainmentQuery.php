<?php
namespace App\Application;

use App\Domain\Views\DetailedComplainmentView;
use Ramsey\Uuid\UuidInterface;

interface ComplainmentQuery
{
    public function getById(UuidInterface $complainmentId): DetailedComplainmentView;
    public function getByStatusComplainments( string $status = 'new' ): array;
}
