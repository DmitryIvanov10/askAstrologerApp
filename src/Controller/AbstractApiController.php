<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Response\AppJsonResponse;
use App\Exception\AppException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * TODO cover by unit test
 */
class AbstractApiController extends AbstractController
{
    private ?Request $request;
    private AppJsonResponse $jsonResponse;

    /**
     * @throws AppException
     */
    public function __construct(RequestStack $requestStack, AppJsonResponse $jsonResponse)
    {
        $this->request = $requestStack->getMasterRequest();

        if (null === $this->request) {
            throw new AppException('No request found');
        }

        $this->jsonResponse = $jsonResponse;
    }

    /**
     * @throws BadRequestHttpException
     */
    protected function getJsonDataFromRequest(Request $request): array
    {
        if ($request->getContentType() != 'json' || !$request->getContent()) {
            throw new BadRequestHttpException('Incorrect request json parameters');
        }

        $data = json_decode($request->getContent(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new BadRequestHttpException('invalid json body: ' . json_last_error_msg());
        }

        return is_array($data) ? $data : [];
    }

    protected function getPositiveResponse($data, string $message = ''): JsonResponse
    {
        $this->jsonResponse->setData($data);
        $this->jsonResponse->setMessage($message);

        return $this->json(
            $this->jsonResponse
        );
    }
}
