<?php
declare(strict_types=1);

namespace App\Service;

use App\Dto\AstrologerDto;
use App\Dto\AstrologerServiceDto;
use App\Dto\ServiceDto;
use App\Entity\Astrologer;
use App\Entity\AstrologerService;
use App\Entity\Service;
use App\Exception\AppException;
use App\Exception\BadArgumentException;
use App\Exception\InfrastructureException;
use App\Exception\NotFoundException;
use App\Repository\AstrologerRepository;
use App\Repository\AstrologerServiceRepository;
use App\Repository\ServiceRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AstrologerServiceService
{
    private ServiceRepository $serviceRepository;
    private AstrologerRepository $astrologerRepository;
    private AstrologerServiceRepository $astrologerServiceRepository;
    private ImageUploader $imageUploader;

    public function __construct(
        ServiceRepository $serviceRepository,
        AstrologerRepository $astrologerRepository,
        AstrologerServiceRepository $astrologerServiceRepository,
        ImageUploader $imageUploader
    ) {
        $this->serviceRepository = $serviceRepository;
        $this->astrologerRepository = $astrologerRepository;
        $this->astrologerServiceRepository = $astrologerServiceRepository;
        $this->imageUploader = $imageUploader;
    }

    /**
     * @throws InfrastructureException
     * @throws BadArgumentException
     */
    public function createService(ServiceDto $serviceDto): Service
    {
        return $this->serviceRepository->createService($serviceDto);
    }

    /**
     * @throws NotFoundException
     */
    public function getService(int $id): Service
    {
        return $this->serviceRepository->get($id);
    }

    /**
     * @return Service[]
     */
    public function findServices(): array
    {
        return $this->serviceRepository->findAll();
    }

    /**
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function deleteService(int $id): void
    {
        $this->serviceRepository->deleteService($id);
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     */
    public function createAstrologer(AstrologerDto $astrologerDto): Astrologer
    {
        return $this->astrologerRepository->createAstrologer($astrologerDto);
    }

    /**
     * @throws NotFoundException
     */
    public function getAstrologer(int $id): Astrologer
    {
        return $this->astrologerRepository->get($id);
    }

    /**
     * @return Astrologer[]
     */
    public function findAstrologers(): array
    {
        return $this->astrologerRepository->findAll();
    }

    /**
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function deleteAstrologer(int $id): void
    {
        $this->astrologerRepository->deleteAstrologer($id);
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function addServiceToAstrologer(AstrologerServiceDto $astrologerServiceDto): AstrologerService
    {
        $astrologer = $this->getAstrologer($astrologerServiceDto->getAstrologerId());
        $service = $this->getService($astrologerServiceDto->getServiceId());

        $astrologerService = $this->astrologerServiceRepository->createAstrologerService(
            $astrologer,
            $service,
            $astrologerServiceDto
        );

        $astrologer->addAstrologerService($astrologerService);
        $service->addAstrologerService($astrologerService);

        return $astrologerService;
    }

    /**
     * @throws BadArgumentException
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function updateAstrologerService(AstrologerServiceDto $astrologerServiceDto): AstrologerService
    {
        return $this->astrologerServiceRepository->updateAstrologerService(
            $astrologerServiceDto
        );
    }

    /**
     * @throws InfrastructureException
     * @throws NotFoundException
     */
    public function deleteAstrologerService(AstrologerServiceDto $astrologerServiceDto): void
    {
        $this->astrologerServiceRepository->deleteAstrologerService(
            $astrologerServiceDto
        );
    }

    /**
     * @return AstrologerService[]
     */
    public function findAstrologerServices(int $id): array
    {
        return $this->astrologerRepository->findAstrologerServices($id);
    }

    /**
     * @return array astrologerId => AstrologerService[]
     */
    public function findAstrologersServices(): array
    {
        return $this->astrologerRepository->findAstrologersServices();
    }

    /**
     * @throws AppException
     * @throws NotFoundException
     */
    public function updateAstrologerImage(int $id, UploadedFile $imageFile): Astrologer
    {
        $astrologer = $this->getAstrologer($id);

        $astrologer->setImageFilename($this->imageUploader->upload($imageFile));

        return $this->astrologerRepository->save($astrologer);
    }
}
