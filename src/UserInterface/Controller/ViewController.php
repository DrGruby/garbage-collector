<?php
namespace App\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class ViewController extends Controller
{
    /**
     * @Route("/home", name="app_home_view")
     */
    public function homeView()
    {
        $renderData = [
            'viewName' => 'Home',
        ];

        return $this->render('MainView.html.twig', $renderData);
    }

    /**
     * @Route("/app", name="app_app_view")
     */
    public function appView()
    {
        $renderData = [
            'viewName' => 'App',
        ];

        return $this->render('MainView.html.twig', $renderData);
    }

    /**
     * @Route("/complainments", name="app_complainments_view")
     */
    public function complainmentView()
    {
        $renderData = [
            'viewName' => 'Complainment',
            'types' => [
                'type_1_complainment',
                'type_2_complainment',
                'type_3_complainment',
            ],
        ];

        return $this->render('MainView.html.twig', $renderData);
    }
}
