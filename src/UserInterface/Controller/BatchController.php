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
    protected function convertToObject(array $keys, array $data)
    {
        $object = [];

        for ($c=0; $c < count($keys); $c++) {
            $object[$keys[$c]] = $data[$c];
        }

        return $object;
        // return (object)$object;
    }

    protected function extractDataFromFile(string $file, int $maxRow = 10, int $minKeys = 7, callable $callback )
    {
        $row = 1;
        $response = '';
        if (($handle = fopen($file, "r")) !== FALSE) {
            $keys = $this->getKeys($handle,$minKeys);
            while (($data = fgetcsv($handle, 0, ",")) !== FALSE && $row <= $maxRow) {
                $num = count($data);
                $object = $this->convertToObject($keys,$data);
                $callback($object);
                $response .= "<p> ".json_encode($object)."</p>";
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
        $response = $this->extractDataFromFile($file, $maxRow, $minKeys);
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
        $finder->files()->in($this->get('kernel')->getProjectDir().'/csv/koma/')->name('*.csv');

        foreach($finder as $file){
            $file = $file->getRealPath();
            $minKeys = 10;
            $maxRow = 10;
            $bucketRepository = $this->get('app.bucket_repository');

            $response = $this->extractDataFromFile($file, $maxRow, $minKeys, function($object) use ($bucketRepository)
            {
                if(!empty($object["Nr pojemnika"])) {
                    // var_dump($object);
                    $position = new Position($object['Szerokość geograficzna'],$object['Długość geograficzna']);
                    $bucket = new Bucket($object['Nr pojemnika'],$object['Typ pojemnika'],$position,2);
                    $bucketRepository->add($bucket);
                }
            });
        }
        return new Response(
            '<html><body>'.$response.'</body></html>'
        );
    }
}
