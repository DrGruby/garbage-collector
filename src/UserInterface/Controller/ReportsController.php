<?php
namespace App\UserInterface\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportsController extends Controller
{
    /**
     * @Route("/reports", name="app_reports_view")
     */
    public function homeView()
    {
        $allLaps = $this->get('app.lap_repository')->getAll();
        $renderData = [
            'viewName' => 'Reports',
            'reportsList' => $allLaps,
        ];

        return $this->render('reports/MainView.html.twig', $renderData);
    }

    /**
     * @Route("/reports/{id}", name="app_reports_lists_view")
     */
    public function reportView($id)
    {
        $reportService = $this->get('app.lap_report');

        $report = $reportService->reportForLap(Uuid::fromString($id));

        var_dump((string)$report->lapId());
        var_dump($report->maxTimeBetweenPickups());
        var_dump($report->pickedGarbage());
        var_dump($report->timeToUnload());
        var_dump($report->totalLapTime());

        return new Response('derp');
    }
}
