<?php
namespace App\UserInterface\Controller;

use App\Domain\Entity\Bucket;
use App\Domain\Entity\Position;
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
     * @Route("/batch2", name="app_batch2")
     */
    public function batch2()
    {
        $finder = new Finder();
        $finder->files()->in($this->get('kernel')->getProjectDir().'/csv/koma/inventeryzacja')->name('*.csv');

        foreach($finder as $file){
            $file = $file->getRealPath();
            $minKeys = 10;
            $maxRow = 40;
            $bucketRepository = $this->get('app.bucket_repository');

            $controller = $this;

            $response = $this->extractDataFromFile($file, function($object) use ($controller)
            {
                var_dump(($object['Typ odpadu']));
                if(!empty($object["Nr pojemnika"])) {
                    // var_dump($object);
                    $position = new Position($object['Szerokość geograficzna'],$object['Długość geograficzna']);
                    $type = $controller->getGarbageType($object['Typ odpadu']);
                    $bucket = new Bucket($object['Nr pojemnika'],$type,$position,2);
                    $controller->get('app.bucket_repository')->add($bucket);
                    return $bucket->id;
                }
            }, $minKeys, $maxRow = -1);
        }
        return new Response(
            '<html><body>'.$response.'</body></html>'
        );
    }

    /**
     * @Route("/batch3", name="app_batch3")
     */
    public function batch3()
    {
        $finder = new Finder();
        $finder->files()->in($this->get('kernel')->getProjectDir().'/csv/koma/eventy')->name('*.csv');

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
}

