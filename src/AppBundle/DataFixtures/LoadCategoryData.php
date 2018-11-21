<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategoryData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $kategoria1 = new Category();
        $kategoria1->setName("Ksiazki i mity");

        $kategoria2 = new Category();
        $kategoria2->setName("Komixy");

        $manager->persist($kategoria1);
        $manager->persist($kategoria2);

        $manager->flush();

    }
}