<?php
namespace App\UserInterface\Controller;

use App\Domain\Entity\Position;
use App\Domain\Events\ComplainmentMade;
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
        // try {
            $request = Request::createFromGlobals();
            $fields = $request->request->all();
    
            if (empty($fields)) {
                // or some different view
                return $this->render('ErrorView.html.twig');
            }

            $position = new Position(214.12, 1251,12);
            $complainmentService = $this->get('app.complainment_service');
            $complainmentMade = new ComplainmentMade(
                $fields['description'],
                $position,
                $fields['complainment']
            );

            $complainmentService->newComplainment($complainmentMade);
        // } catch(\Exception $e) {
        //     print_r($e->getTraceAsString());
        //     return $this->render('ErrorView.html.twig');
        // }

        return $this->render('ComplainmentRecievedView.html.twig');
    }
}
