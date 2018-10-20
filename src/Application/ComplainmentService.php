<?php
namespace App\Application;

use App\Domain\Entity\Complainment;
use App\Domain\Events\ComplainmentMade;
use App\Infrastructure\NewComplainmentFactory;

class ComplainmentService
{
    private $complainmentRepository;

    public function __construct(ComplainmentRepository $complainmentRepository) {
        $this->complainmentRepository = $complainmentRepository;
    }

    public function newComplainment(ComplainmentMade $event)
    {
        $newComplainment = (new NewComplainmentFactory)->create($event);
        $this->complainmentRepository->add($newComplainment);
    }
}
