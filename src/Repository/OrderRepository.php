<?php
declare(strict_types=1);

namespace App\Repository;

use App\Dto\OrderDto;
use App\Entity\Astrologer;
use App\Entity\Order;
use App\Entity\Service;
use App\Exception\BadArgumentException;
use App\Exception\InfrastructureException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    private ValidatorInterface $validator;

    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, Order::class);
        $this->validator = $validator;
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     */
    public function save(Order $order): Order
    {
        $errors = $this->validator->validate($order);

        if (count($errors) > 0) {
            throw new BadArgumentException((string)$errors);
        }

        $em = $this->getEntityManager();

        try {
            $em->persist($order);
            $em->flush();
        } catch (ORMException $exception) {
            throw new InfrastructureException('Cannot save order to the DataBase', 0, $exception);
        }

        return $order;
    }

    /**
     * @throws InfrastructureException
     * @throws BadArgumentException
     */
    public function createOrder(OrderDto $orderDto, Astrologer $astrologer, Service $service): Order
    {
        $order = new Order();
        $order->setAstrologer($astrologer);
        $order->setService($service);
        $order->setPrice($orderDto->getPrice());
        $order->setCustomerEmail($orderDto->getCustomerEmail());
        $order->setCustomerName($orderDto->getCustomerName());

        return $this->save($order);
    }
}
