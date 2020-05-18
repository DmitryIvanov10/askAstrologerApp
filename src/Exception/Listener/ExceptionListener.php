<?php
declare(strict_types=1);

namespace App\Exception\Listener;

use App\Controller\Response\AppJsonResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * TODO cover with unit test
 */
final class ExceptionListener
{
    private NormalizerInterface $serializer;
    private AppJsonResponse $response;

    public function __construct(NormalizerInterface $serializer, AppJsonResponse $response)
    {
        $this->serializer = $serializer;
        $this->response = $response;
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(ExceptionEvent $event)
    {
        $response = new JsonResponse();

        $exception = $event->getThrowable();

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse(
            $response->setData(
                $this->getResponseData($event)
            )
        );
    }

    /**
     * @throws ExceptionInterface
     */
    private function getResponseData(ExceptionEvent $event): array
    {
        $exception = $event->getThrowable();

        $this->response->setSuccess(false);
        $this->response->setException($exception);

        return $this->serializer->normalize(
            $this->response
        );
    }
}
