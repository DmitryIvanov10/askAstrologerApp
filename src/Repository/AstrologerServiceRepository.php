<?php
declare(strict_types=1);

namespace App\Repository;

use App\Dto\AstrologerServiceDto;
use App\Entity\Astrologer;
use App\Entity\AstrologerService;
use App\Entity\Service;
use App\Exception\BadArgumentException;
use App\Exception\InfrastructureException;
use App\Exception\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method AstrologerService|null find($id, $lockMode = null, $lockVersion = null)
 * @method AstrologerService|null findOneBy(array $criteria, array $orderBy = null)
 * @method AstrologerService[]    findAll()
 * @method AstrologerService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @codeCoverageIgnore
 */
class AstrologerServiceRepository extends ServiceEntityRepository
{
    private ValidatorInterface $validator;

    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, AstrologerService::class);
        $this->validator = $validator;
    }

    /**
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function getByAstrologerIdAndServiceId(int $astrologerId, int $serviceId): AstrologerService
    {
        $qb = $this->createQueryBuilder('asv');

        $qb
            ->join('asv.astrologer', 'a')
            ->join('asv.service', 's')
            ->andWhere(
                $qb->expr()->andX(
                    $qb->expr()->eq('a.id', $astrologerId),
                    $qb->expr()->eq('s.id', $serviceId)
                )
            );

        try {
            /** @var AstrologerService $astrologerService */
            return $qb->getQuery()->getSingleResult();
        } catch (NoResultException $exception) {
            throw new NotFoundException(
                AstrologerService::class, [
                'astrologerId' => $astrologerId,
                'serviceId' => $serviceId
            ], $exception
            );
        } catch (NonUniqueResultException $exception) {
            throw new InfrastructureException(
                sprintf(
                    'Non-unique astrologer service for astrologerId %s and serviceId %s',
                    $astrologerId,
                    $serviceId
                ), 0, $exception
            );
        }
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     */
    public function createAstrologerService(
        Astrologer $astrologer,
        Service $service,
        AstrologerServiceDto $astrologerServiceDto
    ): AstrologerService {
        $astrologerService = new AstrologerService();
        $astrologerService->setAstrologer($astrologer);
        $astrologerService->setService($service);
        $astrologerService->setPrice($astrologerServiceDto->getPrice());

        return $this->saveAstrologerService($astrologerService);
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function updateAstrologerService(AstrologerServiceDto $astrologerServiceDto): AstrologerService
    {
        $astrologerService = $this->getByAstrologerServiceDto($astrologerServiceDto);

        $astrologerService->setPrice($astrologerServiceDto->getPrice());

        return $this->saveAstrologerService($astrologerService);
    }

    /**
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function deleteAstrologerService(AstrologerServiceDto $astrologerServiceDto): void
    {
        $astrologerService = $this->getByAstrologerServiceDto($astrologerServiceDto);

        $em = $this->getEntityManager();

        try {
            $em->remove($astrologerService);
            $em->flush();
        }  catch (ORMException $exception) {
            throw new InfrastructureException('Cannot delete astrologer service', 0, $exception);
        }
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     */
    private function saveAstrologerService(AstrologerService $astrologerService): AstrologerService
    {
        $errors = $this->validator->validate($astrologerService);

        if (count($errors) > 0) {
            throw new BadArgumentException((string)$errors);
        }

        $em = $this->getEntityManager();

        try {
            $em->persist($astrologerService);
            $em->flush();
        } catch (ORMException $exception) {
            throw new InfrastructureException('Cannot create astrologer service', 0, $exception);
        }

        return $astrologerService;
    }

    /**
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    private function getByAstrologerServiceDto(AstrologerServiceDto $astrologerServiceDto): AstrologerService
    {
        return $this->getByAstrologerIdAndServiceId(
            $astrologerServiceDto->getAstrologerId(),
            $astrologerServiceDto->getServiceId()
        );
    }
}
