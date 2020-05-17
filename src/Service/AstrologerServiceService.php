<?php
declare(strict_types=1);

namespace App\Service;

use App\Dto\AstrologerDto;
use App\Dto\ServiceDto;
use App\Entity\Astrologer;
use App\Entity\Service;
use App\Exception\BadArgumentException;
use App\Exception\InfrastructureException;
use App\Exception\NotFoundException;
use App\Repository\AstrologerRepository;
use App\Repository\ServiceRepository;

class AstrologerServiceService
{
    private ServiceRepository $serviceRepository;
    private AstrologerRepository $astrologerRepository;

    public function __construct(ServiceRepository $serviceRepository, AstrologerRepository $astrologerRepository)
    {
        $this->serviceRepository = $serviceRepository;
        $this->astrologerRepository = $astrologerRepository;
    }

    /**
     * @throws InfrastructureException
     * @throws BadArgumentException
     */
    public function createService(ServiceDto $serviceDto): void
    {
        $this->serviceRepository->createService($serviceDto);
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
    public function createAstrologer(AstrologerDto $astrologerDto): void
    {
        $this->astrologerRepository->createAstrologer($astrologerDto);
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
}
