<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\OrderStatus;
use App\Exception\InfrastructureException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderStatus[]    findAll()
 * @method OrderStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderStatusRepository extends ServiceEntityRepository
{
    private const STATUS_NEW = 1;
    private const STATUS_PAID = 10;
    private const STATUS_CLOSED = 20;
    private const STATUS_CANCELLED = 30;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderStatus::class);
    }

    /**
     * @throws InfrastructureException
     */
    public function getStatusNew(): OrderStatus
    {
        return $this->getStatus(self::STATUS_NEW);
    }

    /**
     * @throws InfrastructureException
     */
    public function getStatusPaid(): OrderStatus
    {
        return $this->getStatus(self::STATUS_PAID);
    }

    /**
     * @throws InfrastructureException
     */
    public function getStatusClosed(): OrderStatus
    {
        return $this->getStatus(self::STATUS_CLOSED);
    }

    /**
     * @throws InfrastructureException
     */
    public function getStatusCancelled(): OrderStatus
    {
        return $this->getStatus(self::STATUS_CANCELLED);
    }

    /**
     * @throws InfrastructureException
     */
    private function getStatus(int $statusName): OrderStatus
    {
        $orderStatus = $this->find($statusName);

        if ($orderStatus instanceof OrderStatus) {
            return $orderStatus;
        }

        throw new InfrastructureException('Order status is corrupted');
    }
}
