<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Score;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ScoreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $clients = $manager->getRepository(Client::class);
        $data = $clients->findAll();

        for ($i = 0; $i < 50; $i++) {
            $score = new Score();
            $score
                ->setClient($data[$i])
                ->setResult(rand(10, 20));
            $manager->persist($score);
        }
        $manager->flush();
    }


}
