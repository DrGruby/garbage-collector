<?php
namespace App\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        $renderData = [
            'viewName' => 'Complainment',
            'types' => [
                'type_1_complainment',
                'type_2_complainment',
                'type_3_complainment',
            ],
        ];

        return $this->render('reportView.html.twig', $renderData);
    }
}
