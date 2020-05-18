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
use App\Repository\ServiceRepository;

class OrderService
{
    private OrderRepository $orderRepository;
    private AstrologerRepository $astrologerRepository;
    private ServiceRepository $serviceRepository;

    public function __construct(
        OrderRepository $orderRepository,
        AstrologerRepository $astrologerRepository,
        ServiceRepository $serviceRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->astrologerRepository = $astrologerRepository;
        $this->serviceRepository = $serviceRepository;
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
            return $this->orderRepository->createOrder($orderDto, $astrologer, $service);
        }

        throw new BadArgumentException('The astrologer doesn\'t have this service');
    }
}
