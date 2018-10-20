<?php
namespace App\UserInterface\Controller;

use App\Domain\Entity\Bucket;
use App\Domain\Entity\Position;
use App\Domain\Events\TruckUnloaded;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BatchController extends Controller
{
    protected function getGarbageType($type)
    {
        switch($type)
        {  
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
        // return (object)$object;
    }

    protected function extractDataFromFile(string $file, callable $callback, int $minKeys = 7, int $maxRow = 10 )
    {
        $row = 1;
        $response = '';
        if (($handle = fopen($file, "r")) !== FALSE) {
            $keys = $this->getKeys($handle,$minKeys);
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE && ( $row <= $maxRow || $maxRow === -1))  {
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
     * @Route("/batch", name="app_batch")
     */
    public function batch()
    {
        $file = __DIR__.'/../../../csv/Dane 2017 waga Hryniewicze.csv';
        $minKeys = 7;
        $maxRow = 10;
        $response = $this->extractDataFromFile($file, function($object) use ($minKeys) {

        }, $minKeys, $maxRow);
        return new Response(
            '<html><body>'.$response.'</body></html>'
        );
    }

    /**
     * @Route("/komaInvent", name="app_koma_invent")
     */
    public function komaInvent()
    {
        $finder = new Finder();
        $finder->files()->in($this->get('kernel')->getProjectDir().'/csv/koma/inventeryzacja')->name('*.csv');

        foreach($finder as $file){
            $filePath = $file->getRealPath();
            $minKeys = 5;
            $maxRow = 10;
            $bucketRepository = $this->get('app.bucket_repository');

            $controller = $this;
            $district = preg_replace('/.+([0-9]{1}).csv$/', "$1", $file->getFilename());

            $response = $this->extractDataFromFile($filePath, function($object) use ($district, $controller)
            {
                if(!empty($object["Nr pojemnika"])) {
                    $position = new Position($object['Szerokość geograficzna'],$object['Długość geograficzna']);
                    $type = $controller->getGarbageType($object['Typ odpadu']);
                    $bucket = new Bucket($object['Nr pojemnika'],$type,$position,$district);
                    $controller->get('app.bucket_repository')->add($bucket);
                    return $bucket->id();
                }
            }, $minKeys, $maxRow);
        }
        return new Response(
            '<html><body>'.$response.'</body></html>'
        );
    }

    /**
     * @Route("/blysk", name="app_blysk")
     */
    public function blysk()
    {
        $finder = new Finder();
        $finder->files()->in($this->get('kernel')->getProjectDir().'/csv/blysk')->name('*.csv');

        foreach($finder as $file){
            $file = $file->getRealPath();
            $minKeys = 25;
            $maxRow = 10;
            // $bucketRepository = $this->get('app.bucket_repository');
            $bucketRepository = 1;
            $response = $this->extractDataFromFile($file, function($object) use ($bucketRepository)
            {
                var_dump($object);
                // if(!empty($object["Nr pojemnika"])) {
                //     $position = new Position($object['Szerokość geograficzna'],$object['Długość geograficzna']);
                //     $bucket = new Bucket($object['Nr pojemnika'],$object['Typ pojemnika'],$position,2);
                //     $bucketRepository->add($bucket);
                // }
            }, $minKeys, $maxRow);
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
            $maxRow = 100;
            // $bucketRepository = $this->get('app.bucket_repository');
            $bucketRepository = 1;
            $response = $this->extractDataFromFile($file, function($object) use ($bucketRepository)
            {
                $district = preg_replace('/<Sektor ([0-9]{1}) >/', '$1',$object['Info']);
                if(is_numeric($district))
                {
                    $truckUnload = new TruckUnloaded(
                        $district,
                        $this->getGarbageType($object['Asortyment']),
                        $object['Netto Rozl'], 
                        new \DateTimeImmutable($object['DataBrutto']), 
                        $object['Nr Rej.']
                    );
                    return print_r($truckUnload, true);
                }
                return false;
                // if(!empty($object["Nr pojemnika"])) {
                //     $position = new Position($object['Szerokość geograficzna'],$object['Długość geograficzna']);
                //     $bucket = new Bucket($object['Nr pojemnika'],$object['Typ pojemnika'],$position,2);
                //     $bucketRepository->add($bucket);
                // }
            }, $minKeys, $maxRow);
        }
        return new Response(
            '<html><body>'.$response.'</body></html>'
        );
    }
}

