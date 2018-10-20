<?php
namespace App\Application;

use App\Domain\Entity\Complainment;
use App\Domain\Events\ComplainmentMade;
use App\Domain\Events\ComplainmentBeingProcessed;
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

    public function setComplainmentToProcess(ComplainmentBeingProcessed $event)
    {
        $complainment = $this->complainmentRepository->get($event->id());
        $complainment->process($event->status(), $event->processingStartTime());
    }
}
