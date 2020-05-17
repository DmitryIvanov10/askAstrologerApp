<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\OrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist(new OrderStatus(1, 'new'));
        $manager->persist(new OrderStatus(10, 'paid'));
        $manager->persist(new OrderStatus(20, 'closed'));
        $manager->persist(new OrderStatus(30, 'cancelled'));

        $manager->flush();
    }
}
