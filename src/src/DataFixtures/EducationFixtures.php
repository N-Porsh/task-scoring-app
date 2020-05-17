<?php

namespace App\DataFixtures;

use App\Entity\Education;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EducationFixtures extends Fixture
{
    private static $educationOptions = [
        "Среднее Образование",
        "Специальное образование",
        "Высшее образование"
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::$educationOptions as $value) {
            $education = new Education();
            $education->setValue($value);
            $manager->persist($education);
        }
        $manager->flush();
    }
}
