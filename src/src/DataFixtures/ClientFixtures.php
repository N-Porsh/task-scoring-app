<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends BaseFixture
{
    private static $emailDomains = [
        'gmail.com',
        'yandex.ru',
        'mail.ru',
        'example.ee',
        'hot.ee'
    ];

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Client::class, 50, function (Client $client) {
            $client->setName($this->faker->firstName)
                ->setSurname($this->faker->lastName)
                ->setEmail($this->faker->userName . '@' . $this->faker->randomElement(self::$emailDomains))
                ->setPhone(7 . rand(1000000000, 9999999999))
                ->setEducationId($this->faker->numberBetween(1, 3))
                ->setProcessData($this->faker->boolean(80));
        });
        $manager->flush();
    }

}
