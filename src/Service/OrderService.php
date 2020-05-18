<?php
declare(strict_types=1);

namespace App\Service;

use App\Dto\OrderDto;
use App\Entity\Order;
use App\Exception\BadArgumentException;
use App\Exception\InfrastructureException;
use App\Exception\NotFoundException;
use App\Repository\AstrologerRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStatusRepository;
use App\Repository\ServiceRepository;

class OrderService
{
    private OrderRepository $orderRepository;
    private AstrologerRepository $astrologerRepository;
    private ServiceRepository $serviceRepository;
    private OrderStatusRepository $orderStatusRepository;

    public function __construct(
        OrderRepository $orderRepository,
        AstrologerRepository $astrologerRepository,
        ServiceRepository $serviceRepository,
        OrderStatusRepository $orderStatusRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->astrologerRepository = $astrologerRepository;
        $this->serviceRepository = $serviceRepository;
        $this->orderStatusRepository = $orderStatusRepository;
    }

    /**
     * @throws InfrastructureException
     * @throws BadArgumentException
     * @throws NotFoundException
     */
    public function createOrder(OrderDto $orderDto): Order
    {
        $astrologer = $this->astrologerRepository->get($orderDto->getAstrologerId());
        $service = $this->serviceRepository->get($orderDto->getServiceId());

        if ($this->astrologerRepository->isAstrologerHasService(
            $orderDto->getAstrologerId(),
            $orderDto->getServiceId()
        )) {
            return $this->orderRepository->createOrder(
                $orderDto,
                $astrologer,
                $service,
                $this->orderStatusRepository->getStatusNew()
            );
        }

        throw new BadArgumentException('The astrologer doesn\'t have this service');
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function payOrder(int $id): Order
    {
        return $this->orderRepository->updateOrderStatus(
            $this->orderRepository->get($id),
            $this->orderStatusRepository->getStatusPaid()
        );
    }

    /**
     * @throws NotFoundException
     */
    public function getOrder(int $id): Order
    {
        return $this->orderRepository->get($id);
    }

    /**
     * @return Order[]
     */
    public function findOrders(): array
    {
        return $this->orderRepository->findAll();
    }
}
