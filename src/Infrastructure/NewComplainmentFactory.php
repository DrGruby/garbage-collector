<?php
namespace App\Infrastructure;

use App\Domain\Entity\Complainment;
use App\Domain\Events\ComplainmentMade;

class NewComplainmentFactory
{
    public function create(ComplainmentMade $event)
    {
        return new Complainment(
            null,
            null,
            $event->complainmentSubmitTime(),
            $event->complainmentType(),
            null,
            $event->description(),
            $event->position(),
            null,
            $event->status(),
            $event->submitter()
        );
    }
}
