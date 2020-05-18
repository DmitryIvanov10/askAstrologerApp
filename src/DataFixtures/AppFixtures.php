<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Astrologer;
use App\Entity\OrderStatus;
use App\Entity\Service;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->persist(
            $manager,
            ...$this->getOrderStatuses(),
            ...$this->getServices(),
            ...$this->getAstrologers()
        );

        $manager->flush();
    }

    private function persist(ObjectManager $manager, object ...$objects): void
    {
        foreach ($objects as $object) {
            $manager->persist($object);
        }
    }

    /**
     * @param ObjectManager $manager
     * @return OrderStatus[]
     */
    private function getOrderStatuses(): array
    {
        return [
            new OrderStatus(1, 'new'),
            new OrderStatus(10, 'paid'),
            new OrderStatus(20, 'closed'),
            new OrderStatus(30, 'cancelled')
        ];
    }

    /**
     * @param ObjectManager $manager
     * @return Service[]
     */
    private function getServices(): array
    {
        return [
            $this->createService('Натальная карта', 'Персональный гороскоп, построенный на момент рождения человека.'),
            $this->createService('Детальный гороскоп'),
            $this->createService('Отчет совместимости', 'Совместим несовместимое'),
            $this->createService('Гороскоп на год')
        ];
    }

    private function createService(string $name, ?string $description = null): Service
    {
        return (new Service())
            ->setName($name)
            ->setDescription($description);
    }

    /**
     * @param ObjectManager $manager
     * @return Astrologer[]
     */
    private function getAstrologers(): array
    {
        return [
            $this->createAstrologer('Люси', '15.03.1986', 'astro_lucy@gmail.com', 'Гадаю с детства', 'Загадочная'),
            $this->createAstrologer('Кевин', '26.07.1959', 'super_kev@gmail.com', 'Все могу', 'Спейси'),
        ];
    }

    private function createAstrologer(
        string $name,
        string $dateOfBirth,
        string $email,
        string $description,
        ?string $surname = null
    ): Astrologer {
        return (new Astrologer())
            ->setName($name)
            ->setSurname($surname)
            ->setDateOfBirth(DateTimeImmutable::createFromFormat('d.m.Y', $dateOfBirth))
            ->setEmail($email)
            ->setDescription($description);
    }
}
