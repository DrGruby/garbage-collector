<?php
namespace App\UserInterface\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BucketsController extends Controller
{
    /**
     * @Route("/buckets", name="app_buckets_list_view")
     */
    public function homeView()
    {
        $allBuckets = $this->get('app.bucket_repository')->getAll();
        $renderData = [
            'viewName' => 'Reports',
            'bucketsList' => $allBuckets,
        ];

        return $this->render('buckets/MainView.html.twig', $renderData);
    }

    /**
     * @Route("/buckets/{id}", name="app_bucket_view")
     */
    public function bucketView($id)
    {
        $reportService = $this->get('app.lap_report');

        $bucket = $reportService->reportForBucket(Uuid::fromString($id));
        $renderData = [
            'viewName' => 'Report',
            'bucket' => $bucket,
        ];

        return $this->render('buckets/BucketView.html.twig', $renderData);
    }
}
