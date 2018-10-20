<?php
namespace App\UserInterface\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ComplainmentController extends Controller
{
    /**
     * @Route("/complainment/submit", name="app_complainment_submit")
     */
    public function submitComplainment()
    {
        $error = false;
        $request = Request::createFromGlobals();
        $fields = $request->request->all();

        // failed 404
        if ($error) {
            return $this->render('ErrorView.html.twig');
        }
        // success
        return $this->render('ComplainmentRecievedView.html.twig');
    }
}
