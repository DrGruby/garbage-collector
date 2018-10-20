<?php
namespace App\UserInterface\Controller;

use App\Domain\Entity\Bucket;
use App\Domain\Entity\Position;
use App\Domain\Entity\Truck;
use App\Domain\Entity\Event;
use App\Domain\Events\TruckCollectedPayload;
use App\Domain\Events\TruckDeparted;
use App\Domain\Events\TruckUnloaded;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BatchController extends Controller
{
    protected function getTruck(string $name, string $plates)
    {
        $truck = $this->get('app.truck_repository')->getByPlate($plates);
        if(is_null($truck)) {
            $truck = new Truck($name, $plates);
            $this->get('app.truck_repository')->add($truck);
        }
        return $truck;
    }

    protected function getBucket(string $rfid, string $type, position $position, int $district)
    {
        $bucket = $this->get('app.bucket_repository')->getByRFID($rfid);
        if(is_null($bucket)){
            $bucket = new Bucket($rfid,$type,$position,$district);
            $this->get('app.bucket_repository')->add($bucket);
        }

        return $bucket;
    }

    protected function getGarbageType($type)
    {
        switch($type)
        {  
            case 'suche':
            case "SUCHE":
                return Bucket::GARBAGE_DRY;
            case "szkło":
                return Bucket::GARBAGE_GLASS;
            case "zielone":
            case "popiół":
                return Bucket::GARBAGE_BIODEGRADABLE;
            case  "gabaryty":
                return Bucket::GARBAGE_BULKY;
            default:
                return Bucket::GARBAGE_MIXED;
        }
    }

    protected function convertToObject(array $keys, array $data)
    {
        $object = [];

        for ($c=0; $c < count($keys); $c++) {
            $object[$keys[$c]] = $data[$c];
        }

        return $object;
    }

    protected function extractDataFromFile(string $file, callable $callback, int $minKeys = 7)
    {
        $row = 1;
        $response = '';
        if (($handle = fopen($file, "r")) !== FALSE) {
            $keys = $this->getKeys($handle,$minKeys);
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE)  {
                $object = $this->convertToObject($keys,$data);
                $response .= "<p> ".$callback($object)."</p>";
                $row++;
            }
            fclose($handle);
        }
        return $response;
    }

    protected function getKeys($handle, int $min = 5)
    {  
        $keys = fgetcsv($handle, 0, ",");
        if(!is_array($keys) || $keys[0]==='' || count($keys)<$min)
        {
            $keys = $this->getKeys($handle, $min);
        }
        return $keys;
    }

    /**
     * @Route("/eventsImport", name="app_events_import")
     */
    public function eventsImport()
    {
        $finder = new Finder();
        $finder->files()->in($this->get('kernel')->getProjectDir().'/csv/koma/eventy')->name('*.csv');

        foreach($finder as $file){
            $filePath = $file->getRealPath();
            $minKeys = 5;
            $maxRow = -1;
            $bucketRepository = $this->get('app.bucket_repository');

            $controller = $this;
            $district = preg_replace('/.+([0-9]{1}).csv$/', "$1", $file->getFilename());

            $response = $this->extractDataFromFile($filePath, function($object) use ($district, $controller)
            {
                $bucket = $controller->get('app.bucket_repository')->getByRFID($object['Tag']);
                if(!is_null($bucket)){
                    $time = new \DateTimeImmutable($object['Data odbioru']);
                    $truckName = $object['Pojazd'];
                    $truckPlate = $truckName;
                    if(preg_match('/ /', $truckName))
                    {
                        $truckRaw = explode(' ', $object['Pojazd']);
                        $truckName = preg_replace('/[\(\)]/', '', $truckRaw[1]);
                        $truckPlate = $truckRaw[0];
                    }
                    $truck = $controller->getTruck($truckName, $truckPlate);
                    $garbageCollected = new TruckCollectedPayload(
                        $bucket->rfid(),
                        $bucket->position(),
                        $time,
                        $bucket->garbageType(),
                        $truck->plates()
                    );
                    $event = new Event($time, serialize($garbageCollected));
                    $controller->get('app.event_repository')->add($event);
                }
            }, $minKeys);
        }
        return new Response(
            '<html><body>'.$response.'</body></html>'
        );
    }

    /**
     * @Route("/bucketImport", name="app_bucket_import")
     */
    public function bucketImport()
    {
        $finder = new Finder();
        $finder->files()->in($this->get('kernel')->getProjectDir().'/csv/koma/inventeryzacja')->name('*.csv');

        foreach($finder as $file){
            $filePath = $file->getRealPath();
            $minKeys = 5;
            $bucketRepository = $this->get('app.bucket_repository');

            $controller = $this;
            $district = preg_replace('/.+([0-9]{1}).csv$/', "$1", $file->getFilename());

            $response = $this->extractDataFromFile($filePath, function($object) use ($district, $controller)
            {
                $rfid = $object['Nr pojemnika'];
                if(!empty($rfid)) {
                    $type = $this->getGarbageType($object['Typ odpadu']);
                    $position = new Position($object['Szerokość geograficzna'],$object['Długość geograficzna']);        
                    $bucket = $controller->getBucket($rfid, $type, $position, $district);
                    return $bucket->id();
                }
            }, $minKeys);
        }
        return new Response(
            '<html><body>'.$response.'</body></html>'
        );
    }

    /**
     * @Route("/sort", name="app_sort")
     */
    public function sort()
    {
        $finder = new Finder();
        $finder->files()->in($this->get('kernel')->getProjectDir().'/csv/sortownia')->name('*.csv');

        foreach($finder as $file){
            $file = $file->getRealPath();
            $minKeys = 5;
            $controller = $this;
            $response = $this->extractDataFromFile($file, function($object) use ($controller)
            {
                $district = preg_replace('/<Sektor ([0-9]{1}) >/', '$1',$object['Info']);
                if(is_numeric($district) && $district == 2)
                {
                    $time = new \DateTimeImmutable($object['DataBrutto']);
                    $truckUnload = new TruckUnloaded(
                        $district,
                        $controller->getGarbageType($object['Asortyment']),
                        $object['Netto Rozl'], 
                        $time, 
                        ltrim($object['Nr Rej.'], 0)
                    );
                    $event = new Event($time, serialize($truckUnload));
                    $controller->get('app.event_repository')->add($event);
                    return print_r($truckUnload, true);
                }
                return false;
            }, $minKeys);
        }
        return new Response(
            '<html><body>'.$response.'</body></html>'
        );
    }

    /**
     * @Route("/garbageImport", name="app_garbageImport")
     */
    public function garbageImport()
    {
        $finder = new Finder();
        $finder->files()->in($this->get('kernel')->getProjectDir().'/csv/spalarnia')->name('*.csv');

        foreach($finder as $file){
            $file = $file->getRealPath();
            $minKeys = 5;
            $controller = $this;
            $response = $this->extractDataFromFile($file, function($object) use ($controller)
            {
                $district = preg_replace('/SEKTOR ([0-9]{1})/', '$1',$object['POCHODZENIE']);

                if(is_numeric($district) && $district == 2)
                {
                    $time = new \DateTimeImmutable($object['DATA 1 WAŻENIA']);
                    $truckUnload = new TruckUnloaded(
                        $district,
                        $controller->getGarbageType($object['PRODUKT']),
                        $object['NETTO'], 
                        $time, 
                        ltrim($object['POJAZD/ WAGON'],0)
                    );

                    $event = new Event($time, serialize($truckUnload));
                    $controller->get('app.event_repository')->add($event);
                    return print_r($truckUnload, true);
                }
                return false;
            }, $minKeys);
        }
        return new Response(
            '<html><body>'.$response.'</body></html>'
        );
    }
}

