<?php

namespace App\DataFixtures;

use App\Entity\Score;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ScoreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 50; $i++) {
            $score = new Score();
            $score->setClientId($i)
                ->setResult(rand(10, 20));
            $manager->persist($score);
        }
        $manager->flush();
    }


}
