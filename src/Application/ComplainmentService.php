<?php
namespace App\Application;

use App\Domain\Entity\Complainment;
use App\Domain\Events\ComplainmentMade;
use App\Domain\Events\ComplainmentBeingProcessed;
use App\Domain\Events\ComplainmentRejected;
use App\Domain\Events\ComplainmentConfirmed;
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

    public function setReject(ComplainmentRejected $event)
    {
        $complainment = $this->complainmentRepository->get($event->id());
        $complainment->reject($event->status(), $event->complainmentCloseTime(), $event->rejectionMessage());
    }

    public function setConfirm(ComplainmentConfirmed $event)
    {
        $complainment = $this->complainmentRepository->get($event->id());
        $complainment->confirm($event->status(), $event->complainmentCloseTime(), $event->confirmationMessage());
    }
}
