<?php
declare(strict_types=1);

namespace App\Controller;

use App\Dto\AstrologerDto;
use App\Dto\ServiceDto;
use App\Exception\BadArgumentException;
use App\Exception\InfrastructureException;
use App\Exception\NotFoundException;
use App\Service\AstrologerServiceService;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use TypeError;

class AstrologerServiceController extends AbstractApiController
{
    /**
     * @throws InfrastructureException
     * @throws BadArgumentException
     * @throws BadRequestHttpException
     */
    public function createService(Request $request, AstrologerServiceService $astrologerServiceService): JsonResponse
    {
        $serviceData = $this->getJsonDataFromRequest($request);

        try {
            $serviceDto = (new ServiceDto())
                ->setName($serviceData['name'])
                ->setDescription($serviceData['description'] ?? null);
        } catch (TypeError $exception) {
            throw new BadRequestHttpException('Incorrect input data');
        }

        $astrologerServiceService->createService($serviceDto);

        return $this->getPositiveResponse(null, 'Service successfully created');
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
        $astrologerData = $this->getJsonDataFromRequest($request);

        try {
            $astrologerDto = (new AstrologerDto())
                ->setName($astrologerData['name'])
                ->setSurname($astrologerData['surname'] ?? null)
                ->setDateOfBirth(DateTimeImmutable::createFromFormat('d.m.Y', $astrologerData['dateOfBirth']))
                ->setEmail($astrologerData['email'])
                ->setDescription($astrologerData['description']);
        } catch (TypeError $exception) {
            throw new BadRequestHttpException('Incorrect input data');
        }

        $astrologerServiceService->createAstrologer($astrologerDto);

        return $this->getPositiveResponse(null, 'Astrologer successfully created');
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

//    public function createAstrologer(Request $request, AstrologerServiceService $astrologerServiceService): JsonResponse
//    {
//        $astrologer = new Astrologer();
//
//        $form = $this->createForm(AstrologerType::class, $astrologer);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            /** @var UploadedFile $imageFile */
//            $imageFile = $form['image']->getData();
//
//            if ($imageFile) {
//                $imageFileName = $fileUploader->upload($imageFile);
//                $astrologer->setImageFilename($imageFileName);
//            }
//
//        }
//    }
}
