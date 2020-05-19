<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EducationFixtures extends Fixture
{
    public static $educationOptions = [
        "Среднее Образование",
        "Специальное образование",
        "Высшее образование"
    ];

    public function load(ObjectManager $manager)
    {

    }
}
