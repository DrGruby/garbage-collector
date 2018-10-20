<?php
namespace App\UserInterface\Controller;

use App\Domain\Entity\Complainment;
use App\Domain\Entity\Position;
use App\Domain\Events\ComplainmentMade;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ComplainmentController extends Controller
{
    /**
     * @Route("/complainments/submit-test", name="app_complainments_submit_test")
     */
    public function submitComplainmentTest()
    {
        $complainment = new Complainment(
            null,
            null,
            new \DateTimeImmutable(),
            'Test Complainment Type',
            null,
            'testDescription',
            new Position(12, 12),
            null,
            'new',
            'test@email.test'
        );

        $complainmentRepository = $this->get('app.complainment_repository');
        $complainmentRepository->add($complainment);

        return new Response($complainment->id());
    }

    /**
     * @Route("/complainments/submit", name="app_complainments_submit")
     */
    public function submitComplainment()
    {
        try {
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
        } catch(\Exception $e) {
            return $this->render('ErrorView.html.twig');
        }

        return $this->render('ComplainmentRecievedView.html.twig');
    }

    /**
     * @Route("admin/complainments/show-new", name="app_admin_complainments_show_new")
     */
    public function adminShowNewComplainments()
    {
        $complainments = $this->get('app.complainment_query')->getNewComplainments();

        $mapped = array_map(function($complainment) {
            return [
                'complainmentType' => $complainment->complainmentType(),
                'id' => $complainment->id(),
                'status' => $complainment->status(),
                'submitter' => $complainment->submitter(),
            ];
        }, $complainments);

        return $this->render('NewComplainmentsView.html.twig', ['mapped' => $mapped]);
    }
    
    /**
     * @Route("admin/complainments/{complainmentId}", name="app_admin_complainments_byid")
     */
    public function adminGetById($complainmentId)
    {
        try {
            $complainment = $this->get('app.complainment_query')->getById(Uuid::fromString($complainmentId));

            $mapped = [
                'id' => $complainment->id(),
                'complainmentCloseTime' => $complainment->complainmentCloseTime(),
                'complainmentProcessingStartTime' => $complainment->complainmentProcessingStartTime(),
                'complainmentSubmitTime' => $complainment->complainmentSubmitTime(),
                'complainmentType' => $complainment->complainmentType(),
                'confirmationMessage' => $complainment->confirmationMessage(),
                'position' => [
                    'latitude' => $complainment->position()->latitude(),
                    'longitude' => $complainment->position()->longitude(),
                ],
                'description' => $complainment->description(),
                'rejectionMessage' => $complainment->rejectionMessage(),
                'status' => $complainment->status(),
                'submitter' => $complainment->submitter()
            ];
    
            return new Response(json_encode($mapped));

        } catch(\Exception $e) {
            return new Response($e->getMessage());
        }
    }

}
