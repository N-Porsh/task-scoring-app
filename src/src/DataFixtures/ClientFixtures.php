<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Education;
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
        $educationArray = [];
        for ($i = 0; $i <= 2; $i++) {
            $education = new Education();
            $education->setValue(EducationFixtures::$educationOptions[$i]);
            $manager->persist($education);
            $educationArray[] = $education;
        }
        $manager->flush();

        $this->createMany(Client::class, 50, function (Client $client) use ($manager, $educationArray) {
            $client->setName($this->faker->firstName)
                ->setSurname($this->faker->lastName)
                ->setEmail($this->faker->userName . '@' . $this->faker->randomElement(self::$emailDomains))
                ->setPhone(7 . rand(9000000000, 9999999999))
                ->setEducation($this->faker->randomElement($educationArray))
                ->setProcessData($this->faker->boolean(80));
        });
        $manager->flush();
    }

}
