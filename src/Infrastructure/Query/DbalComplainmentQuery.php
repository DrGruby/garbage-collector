<?php
namespace App\Infrastructure\Query;

use App\Application\ComplainmentRepository;
use App\Domain\Entity\Complainment;
use App\Domain\Entity\Position;
use App\Domain\Views\DetailedComplainmentView;
use Doctrine\ORM\EntityManager;
use Ramsey\Uuid\UuidInterface;

class DbalComplainmentQuery implements ComplainmentQuery
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Complainment::class);
    }

    public function getById(UuidInterface $complainmentId): DetailedComplainmentView
    {
        $complainment = $this->repository->find($complainmentId);

        if (empty($complainment)) {
            throw new \Exception('a tu ni ma');
        }

        return new DetailedComplainmentView(
            $complainment->id(),
            $complainment->complainmentCloseTime(),
            $complainment->complainmentProcessingStartTime(),
            $complainment->complainmentSubmitTime(),
            $complainment->complainmentType(),
            $complainment->confirmationMessage(),
            $complainment->description(),
            new Position(
                $complainment->position()->latitude(),
                $complainment->position()->longitude()
            ),
            $complainment->rejectionMessage(),
            $complainment->status(),
            $complainment->submitter()
        );
    }

    public function getByStatusComplainments(string $status = 'new'): array
    {
        return $this->repository->findBy(['status' => $status]);
    }
}
