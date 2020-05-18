<?php
declare(strict_types=1);

namespace App\Controller;

use App\Dto\AstrologerDto;
use App\Dto\AstrologerServiceDto;
use App\Dto\ServiceDto;
use App\Exception\AppException;
use App\Exception\BadArgumentException;
use App\Exception\InfrastructureException;
use App\Exception\NotFoundException;
use App\Service\AstrologerServiceService;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use TypeError;

/**
 * @codeCoverageIgnore
 */
class AstrologerServiceController extends AbstractApiController
{
    /**
     * @throws InfrastructureException
     * @throws BadArgumentException
     * @throws BadRequestHttpException
     */
    public function createService(Request $request, AstrologerServiceService $astrologerServiceService): JsonResponse
    {
        $requestData = $this->getJsonDataFromRequest($request);

        try {
            $serviceDto = (new ServiceDto())
                ->setName($requestData['name'])
                ->setDescription($requestData['description'] ?? null);
        } catch (TypeError $exception) {
            throw new BadRequestHttpException('Incorrect request data');
        }

        return $this->getPositiveResponse(
            $astrologerServiceService->createService($serviceDto),
            'Service successfully created'
        );
    }

    /**
     * @throws NotFoundException
     */
    public function getService(int $id, AstrologerServiceService $astrologerServiceService): JsonResponse
    {
        return $this->getPositiveResponse(
            $astrologerServiceService->getService($id)
        );
    }

    public function getServices(AstrologerServiceService $astrologerServiceService): JsonResponse
    {
        return $this->getPositiveResponse(
            $astrologerServiceService->findServices()
        );
    }

    /**
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function deleteService(int $id, AstrologerServiceService $astrologerServiceService): JsonResponse
    {
        $astrologerServiceService->deleteService($id);

        return $this->getPositiveResponse(null, 'Service successfully deleted');
    }


    /**
     * @throws InfrastructureException
     * @throws BadArgumentException
     * @throws BadRequestHttpException
     */
    public function createAstrologer(Request $request, AstrologerServiceService $astrologerServiceService): JsonResponse
    {
        $requestData = $this->getJsonDataFromRequest($request);

        try {
            $astrologerDto = (new AstrologerDto())
                ->setName($requestData['name'])
                ->setSurname($requestData['surname'] ?? null)
                ->setDateOfBirth(DateTimeImmutable::createFromFormat('d.m.Y', $requestData['dateOfBirth']))
                ->setEmail($requestData['email'])
                ->setDescription($requestData['description']);
        } catch (TypeError $exception) {
            throw new BadRequestHttpException('Incorrect request data');
        }

        return $this->getPositiveResponse(
            $astrologerServiceService->createAstrologer($astrologerDto),
            'Astrologer successfully created'
        );
    }

    /**
     * @throws NotFoundException
     */
    public function getAstrologer(int $id, AstrologerServiceService $astrologerServiceService): JsonResponse
    {
        return $this->getPositiveResponse(
            $astrologerServiceService->getAstrologer($id)
        );
    }

    public function getAstrologers(AstrologerServiceService $astrologerServiceService): JsonResponse
    {
        return $this->getPositiveResponse(
            $astrologerServiceService->findAstrologers()
        );
    }

    /**
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function deleteAstrologer(int $id, AstrologerServiceService $astrologerServiceService): JsonResponse
    {
        $astrologerServiceService->deleteAstrologer($id);

        return $this->getPositiveResponse(null, 'Astrologer successfully deleted');
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     * @throws NotFoundException
     * @throws BadRequestHttpException
     */
    public function addServiceToAstrologer(
        int $astrologerId,
        int $serviceId,
        Request $request,
        AstrologerServiceService $astrologerServiceService
    ): JsonResponse {
        $requestData = $this->getJsonDataFromRequest($request);

        try {
            $astrologerServiceDto = (new AstrologerServiceDto())
                ->setAstrologerId($astrologerId)
                ->setServiceId($serviceId)
                ->setPrice((float)$requestData['price']);
        } catch (TypeError $exception) {
            throw new BadRequestHttpException('Incorrect request data');
        }

        return $this->getPositiveResponse(
            $astrologerServiceService->addServiceToAstrologer($astrologerServiceDto),
            'Service successfully added to astrologer'
        );
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     * @throws NotFoundException
     * @throws BadRequestHttpException
     */
    public function updateAstrologerService(
        int $astrologerId,
        int $serviceId,
        Request $request,
        AstrologerServiceService $astrologerServiceService
    ): JsonResponse {
        $requestData = $this->getJsonDataFromRequest($request);

        try {
            $astrologerServiceDto = (new AstrologerServiceDto())
                ->setAstrologerId($astrologerId)
                ->setServiceId($serviceId)
                ->setPrice((float)$requestData['price']);
        } catch (TypeError $exception) {
            throw new BadRequestHttpException('Incorrect request data');
        }

        return $this->getPositiveResponse(
            $astrologerServiceService->updateAstrologerService($astrologerServiceDto),
            'Astrologer service successfully updated'
        );
    }

    /**
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function deleteAstrologerService(
        int $astrologerId,
        int $serviceId,
        AstrologerServiceService $astrologerServiceService
    ): JsonResponse {
        $astrologerServiceDto = (new AstrologerServiceDto())
            ->setAstrologerId($astrologerId)
            ->setServiceId($serviceId);

        $astrologerServiceService->deleteAstrologerService($astrologerServiceDto);

        return $this->getPositiveResponse(null, 'Astrologer service successfully deleted');
    }

    public function getAstrologerServices(int $id, AstrologerServiceService $astrologerServiceService): JsonResponse
    {
        return $this->getPositiveResponse(
            $astrologerServiceService->findAstrologerServices($id)
        );
    }

    public function getAstrologersServices(AstrologerServiceService $astrologerServiceService): JsonResponse
    {
        return $this->getPositiveResponse(
            $astrologerServiceService->findAstrologersServices()
        );
    }

    /**
     * @throws BadRequestHttpException
     * @throws AppException
     * @throws NotFoundException
     */
    public function updateAstrologerImage(
        int $id,
        Request $request,
        AstrologerServiceService $astrologerServiceService
    ): JsonResponse {
        $imageFile = $request->files->get('image');

        if (!$imageFile instanceof UploadedFile) {
            throw new BadRequestHttpException('No image file in the request');
        }

        return $this->getPositiveResponse(
            $astrologerServiceService->updateAstrologerImage($id, $imageFile),
            'Astrologer\'s image successfully updated'
        );
    }
}
