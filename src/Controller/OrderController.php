<?php
declare(strict_types=1);

namespace App\Controller;

use App\Dto\OrderDto;
use App\Exception\BadArgumentException;
use App\Exception\InfrastructureException;
use App\Exception\NotFoundException;
use App\Service\OrderService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use TypeError;

/**
 * @codeCoverageIgnore
 */
class OrderController extends AbstractApiController
{
    /**
     * @throws InfrastructureException
     * @throws BadArgumentException
     * @throws NotFoundException
     * @throws BadRequestHttpException
     */
    public function createOrder(Request $request, OrderService $orderService): JsonResponse
    {
        $requestData = $this->getJsonDataFromRequest($request);

        try {
            $orderDto = (new OrderDto())
                ->setAstrologerId($requestData['astrologerId'])
                ->setServiceId($requestData['serviceId'])
                ->setPrice($requestData['price'])
                ->setCustomerEmail($requestData['customerEmail'])
                ->setCustomerName($requestData['customerName'] ?? null);
        } catch (TypeError $exception) {
            throw new BadRequestHttpException('Incorrect request data');
        }

        return $this->getPositiveResponse(
            $orderService->createOrder($orderDto),
            'Order successfully created'
        );
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function payOrder(int $id, OrderService $orderService): JsonResponse
    {
        $order = $orderService->payOrder($id);

        return $this->getPositiveResponse($order, 'Order successfully paid');
    }

    /**
     * @throws NotFoundException
     */
    public function getOrder(int $id, OrderService $orderService): JsonResponse
    {
        return $this->getPositiveResponse(
            $orderService->getOrder($id)
        );
    }

    public function getOrders(OrderService $orderService): JsonResponse
    {
        return $this->getPositiveResponse(
            $orderService->findOrders()
        );
    }
}
