<?php
declare(strict_types=1);

namespace App\Repository;

use App\Dto\AstrologerDto;
use App\Entity\Astrologer;
use App\Entity\AstrologerService;
use App\Exception\BadArgumentException;
use App\Exception\InfrastructureException;
use App\Exception\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method Astrologer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Astrologer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Astrologer[]    findAll()
 * @method Astrologer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AstrologerRepository extends ServiceEntityRepository
{
    private ValidatorInterface $validator;

    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, Astrologer::class);
        $this->validator = $validator;
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     */
    public function save(Astrologer $astrologer): Astrologer
    {
        $errors = $this->validator->validate($astrologer);

        if (count($errors) > 0) {
            throw new BadArgumentException((string)$errors);
        }

        $em = $this->getEntityManager();

        try {
            $em->persist($astrologer);
            $em->flush();
        } catch (ORMException $exception) {
            throw new InfrastructureException('Cannot save astrologer to the DataBase', 0, $exception);
        }

        return $astrologer;
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     */
    public function createAstrologer(AstrologerDto $astrologerDto): Astrologer
    {
        $astrologer = new Astrologer();
        $astrologer->setName($astrologerDto->getName());
        $astrologer->setSurname($astrologerDto->getSurname());
        $astrologer->setDateOfBirth($astrologerDto->getDateOfBirth());
        $astrologer->setEmail($astrologerDto->getEmail());
        $astrologer->setDescription($astrologerDto->getDescription());

        return $this->save($astrologer);
    }

    /**
     * @throws NotFoundException
     */
    public function get(int $id): Astrologer
    {
        $service = $this->find($id);

        if ($service instanceof Astrologer) {
            return $service;
        }

        throw new NotFoundException(Astrologer::class, ['id' => $id]);
    }

    /**
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function deleteAstrologer(int $id): void
    {
        $service = $this->get($id);

        $em = $this->getEntityManager();

        try {
            $em->remove($service);
            $em->flush();
        } catch (ORMException $exception) {
            throw new InfrastructureException('Cannot delete astrologer', 0, $exception);
        }
    }

    /**
     * @return AstrologerService[]
     */
    public function findAstrologerServices(int $id): array
    {
        $qb = $this->_em->createQueryBuilder()
             ->select('asv')
             ->from(AstrologerService::class, 'asv');

        $qb
            ->join('asv.astrologer', 'a')
            ->andWhere($qb->expr()->andX(
                $qb->expr()->eq('a.id', $id),
                $qb->expr()->eq('asv.active', true)
            ));

        return $qb->getQuery()->getResult();
    }

    public function isAstrologerHasService(int $astrologerId, int $serviceId): bool
    {
        $qb = $this->createQueryBuilder('a');

        $qb
            ->join('a.astrologerServices', 'asc')
            ->join('asc.service', 's')
            ->andWhere($qb->expr()->andX(
                $qb->expr()->eq('a.id', $astrologerId),
                $qb->expr()->eq('s.id', $serviceId)
            ));

        return count($qb->getQuery()->getResult()) > 0;
    }

    /**
     * @return array astrologerId => AstrologerService[]
     */
    public function findAstrologersServices(): array
    {
        $qb = $this->_em->createQueryBuilder()
            ->select(['a.id as astrologerId', 'asv as astrologerService'])
            ->from(AstrologerService::class, 'asv');

        $qb
            ->join('asv.astrologer', 'a')
            ->andWhere(
                $qb->expr()->eq('asv.active', true)
            );

        $astrologersServicesData = $qb->getQuery()->getResult();

        $astrologersServices = [];

        foreach ($astrologersServicesData as $astrologerServicesData) {
            $astrologersServices[$astrologerServicesData['astrologerId']][] = $astrologerServicesData['astrologerService'];
        }

        return $astrologersServices;
    }
}
