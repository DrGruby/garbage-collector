<?php
namespace App\UserInterface\Controller;

use App\Domain\Entity\Complainment;
use App\Domain\Entity\Position;
use App\Domain\Events\ComplainmentMade;
use App\Domain\Events\ComplainmentBeingProcessed;
use App\Domain\Events\ComplainmentRejected;
use App\Domain\Events\ComplainmentConfirmed;
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
                $fields['complainment'],
                $fields['email']
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
                'complainmentCloseTime' => $complainment->complainmentCloseTime() ? $complainment->complainmentCloseTime()->format('Y-m-d') : 'Not closed yet...',
                'complainmentProcessingStartTime' => $complainment->complainmentProcessingStartTime() ? $complainment->complainmentProcessingStartTime()->format('Y-m-d') : 'Not started yet...',
                'complainmentSubmitTime' => $complainment->complainmentSubmitTime()->format('Y-m-d'),
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
    
            return $this->render('DetailedComplainView.html.twig', $mapped);

        } catch(\Exception $e) {
            return new Response($e->getMessage());
        }
    }

    /**
     * @Route("admin/complainments/{complainmentId}/process", name="app_admin_complainments_process")
     */
    public function setToProcess($complainmentId)
    {
        $complainmentService = $this->get('app.complainment_service');
        $event = new ComplainmentBeingProcessed(Uuid::fromString($complainmentId));
        
        $complainmentService->setComplainmentToProcess($event);

        return $this->render('ProcessComplainmentView.html.twig', ['id' => $complainmentId]);
    }

    /**
     * @Route("admin/complainments/{complainmentId}/reject", name="app_admin_complainments_reject")
     */
    public function rejectComplain($complainmentId)
    {
        $complainmentService = $this->get('app.complainment_service');
        $event = new ComplainmentRejected(Uuid::fromString($complainmentId), 'TEST REJECTION MESSAGE');
        
        $complainmentService->setReject($event);

        return $this->render('ProcessComplainmentView.html.twig', ['id' => $complainmentId]);
    }

    /**
     * @Route("admin/complainments/{complainmentId}/confirm", name="app_admin_complainments_confirm")
     */
    public function confirmComplain($complainmentId)
    {
        $complainmentService = $this->get('app.complainment_service');
        $event = new ComplainmentConfirmed(Uuid::fromString($complainmentId), 'TEST CONFIRMATION MESSAGE');
        
        $complainmentService->setConfirm($event);

        return $this->render('ProcessComplainmentView.html.twig', ['id' => $complainmentId]);
    }
}
