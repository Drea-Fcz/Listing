<?php

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

require_once 'vendor/autoload.php';

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('en_EN');

        for ($i = 0; $i < 20; $i++) {
            $person = new Person();
            $person->setName($faker->name())
                ->setFirstname($faker->firstName())
                ->setAge(rand(20, 50));

            $manager->persist($person);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
