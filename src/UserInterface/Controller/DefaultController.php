<?php
namespace App\UserInterface\Controller;

use App\Application\TruckRepository;
use App\Application\TruckService;
use App\Domain\Entity\Truck;
use App\Domain\Events\TruckDeparted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/lucky/{max}", name="app_lucky_number")
     */
    public function number($max)
    {
        $number = random_int(0, $max);

        return new Response(
            '<html><body>Lucky number: ' . $number . '</body></html>'
        );
    }

    /**
     * @Route("/test", name="app_test")
     * @param TruckService $service
     * @param TruckRepository $truckRepository
     * @return Response
     * @throws \Exception
     */
    public function test()
    {
        echo 'derp';
        $truckRepository =  $this->get('app.truck_repository');
        $truck = new Truck('volvo', 'BI12345');
        $truckRepository->add($truck);
////        $service->newLap(new TruckDeparted(new \DateTimeImmutable(), $truck->plates()));
        return new Response('derp');
    }
}
