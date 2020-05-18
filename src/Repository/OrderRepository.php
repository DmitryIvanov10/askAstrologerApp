<?php
declare(strict_types=1);

namespace App\Repository;

use App\Dto\OrderDto;
use App\Entity\Astrologer;
use App\Entity\Order;
use App\Entity\OrderStatus;
use App\Entity\Service;
use App\Exception\BadArgumentException;
use App\Exception\InfrastructureException;
use App\Exception\NotFoundException;
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
     * @throws NotFoundException
     */
    public function get(int $id): Order
    {
        $order = $this->find($id);

        if ($order instanceof Order) {
            return $order;
        }

        throw new NotFoundException(Order::class, ['id' => $id]);
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
    public function createOrder(
        OrderDto $orderDto,
        Astrologer $astrologer,
        Service $service,
        OrderStatus $orderStatus
    ): Order {
        $order = new Order();
        $order->setAstrologer($astrologer);
        $order->setService($service);
        $order->setStatus($orderStatus);
        $order->setPrice($orderDto->getPrice());
        $order->setCustomerEmail($orderDto->getCustomerEmail());
        $order->setCustomerName($orderDto->getCustomerName());
        $order->setCreatedAt();
        $order->setUpdatedAt();

        return $this->save($order);
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     */
    public function updateOrderStatus(Order $order, OrderStatus $orderStatus): Order
    {
        $order->setStatus($orderStatus);

        return $this->save($order);
    }

    /**
     * @return Order[]
     */
    public function findOrders(): array
    {
        $qb = $this->createQueryBuilder('o');

        $ordersData = $qb
            ->select('o as order')
            ->join('o.status', 'os')
            ->join('o.astrologer', 'a')
            ->join('o.service', 's')
            ->getQuery()
            ->getResult();

        $orders = [];

        foreach ($ordersData as $orderData) {
            $orders[] = $orderData['order'];
        }

        return $orders;
    }
}
