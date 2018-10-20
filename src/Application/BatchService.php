<?php

namespace App\Application;

use App\Domain\Events\TruckCollectedPayload;
use App\Domain\Events\TruckDeparted;
use App\Domain\Events\TruckUnloaded;

class BatchService
{
    private $truckService;
    private $eventRepository;

    public function __construct(TruckService $truckService, EventRepository $eventRepository)
    {
        $this->truckService = $truckService;
        $this->eventRepository = $eventRepository;
    }

    public function replayEvents() {
        $events = $this->eventRepository->getAll();

        foreach ($events as $event) {
            $eventToReplay = unserialize($event->data());
            if ($eventToReplay instanceof TruckDeparted) {
                $this->truckService->newLap($eventToReplay);
            } elseif ($eventToReplay instanceof TruckUnloaded) {
                $this->truckService->truckUnloaded($eventToReplay);
            } elseif ($eventToReplay instanceof TruckCollectedPayload) {
                $this->truckService->garbageCollected($eventToReplay);
            } else  {
                echo 'not handled event';
            }
        }
    }
}
