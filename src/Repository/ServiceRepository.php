<?php
declare(strict_types=1);

namespace App\Repository;

use App\Dto\ServiceDto;
use App\Entity\Service;
use App\Exception\BadArgumentException;
use App\Exception\InfrastructureException;
use App\Exception\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method Service|null find($id, $lockMode = null, $lockVersion = null)
 * @method Service|null findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    private ValidatorInterface $validator;

    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, Service::class);
        $this->validator = $validator;
    }

    /**
     * @throws InfrastructureException
     * @throws BadArgumentException
     */
    public function createService(ServiceDto $serviceDto): Service
    {
        $service = new Service();
        $service->setName($serviceDto->getName());
        $service->setDescription($serviceDto->getDescription());

        $errors = $this->validator->validate($service);

        if (count($errors) > 0) {
            throw new BadArgumentException((string)$errors);
        }

        $em = $this->getEntityManager();

        try {
            $em->persist($service);
            $em->flush();
        } catch (ORMException $exception) {
            throw new InfrastructureException('Cannot create service', 0, $exception);
        }

        return $service;
    }

    /**
     * @throws NotFoundException
     */
    public function get(int $id): Service
    {
        $service = $this->find($id);

        if ($service instanceof Service) {
            return $service;
        }

        throw new NotFoundException(Service::class, ['id' => $id]);
    }

    /**
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function deleteService(int $id): void
    {
        $service = $this->get($id);

        $em = $this->getEntityManager();

        try {
            $em->remove($service);
            $em->flush();
        } catch (ORMException $exception) {
            throw new InfrastructureException('Cannot delete service', 0, $exception);
        }
    }
}
