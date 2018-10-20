<?php
namespace App\UserInterface\Controller;

use App\Domain\Entity\Bucket;
use App\Domain\Entity\Event;
use App\Domain\Entity\Position;
use App\Domain\Entity\Truck;
use App\Domain\Events\TruckCollectedPayload;
use App\Domain\Events\TruckDeparted;
use App\Domain\Events\TruckUnloaded;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/test", name="app_test")
     * @return Response
     * @throws \Exception
     */
    public function test()
    {
        $truckRepository = $this->get('app.truck_repository');
        $truck = new Truck('volvo', 'BI12345');
        $truckRepository->add($truck);

        $bucketRepository = $this->get('app.bucket_repository');
        $position = new Position(20, 50);
        $bucket = new Bucket('RF01020102', Bucket::GARBAGE_DRY, $position, 7);
        $bucketRepository->add($bucket);

        $truckDeparted = new TruckDeparted(new \DateTimeImmutable(), $truck->plates());

        $truckService = $this->get('app.truck_service');

        $truckService->newLap($truckDeparted);

        $garbageCollected = new TruckCollectedPayload(
            $bucket->rfid(),
            $position,
            new \DateTimeImmutable(),
            $bucket->garbageType(),
            $truck->plates()
        );
        $truckService->garbageCollected($garbageCollected);

        $truckUnloaded = new TruckUnloaded(7, $bucket->garbageType(), 0.75, new \DateTimeImmutable(), $truck->plates());
        $truckService->truckUnloaded($truckUnloaded);

        return new Response('derp');
    }

    /**
     * @Route("/test-event-store", name="app_test_event_store")
     * @return Response
     * @throws \Exception
     */
    public function testEventStore()
    {
        $eventToReplay = new TruckDeparted(new \DateTimeImmutable(), 'BI12345');

        $event1 = new Event(new \DateTimeImmutable('2017-01-02'), serialize($eventToReplay));
        $event2 = new Event(new \DateTimeImmutable('2017-01-01'), serialize($eventToReplay));
        $event3 = new Event(new \DateTimeImmutable('2017-01-05'), serialize($eventToReplay));
        $event4 = new Event(new \DateTimeImmutable('2017-01-03'), serialize($eventToReplay));

        $eventRepository = $this->get('app.event_repository');

        $eventRepository->add($event1);
        $eventRepository->add($event2);
        $eventRepository->add($event3);
        $eventRepository->add($event4);

        var_dump($eventRepository->getAll());

        return new Response('derp');
    }

    /**
     * @Route("/bucket-test", name="app_bucket_test")
     * @return Response
     * @throws \Exception
     */
    public function bucketTest()
    {
        $bucket = new Bucket('RFID1101', Bucket::GARBAGE_DRY, new Position(20, 50), 1);
        $bucketRepository = $this->get('app.bucket_repository');
        $bucketRepository->add($bucket);

        return new Response($bucket->id());
    }
}
