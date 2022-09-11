<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use App\Entity\Job;
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

        for ($i = 0; $i < 10; $i++) {
            $job = new Job();
            $job->setDesignation($faker->jobTitle());

            $manager->persist($job);
        }

        $hobbies = ['Astrology', 'Acting', 'Music', 'Video Games', 'Hacking', 'Running', 'Climbing'];
        for ($i = 0; $i < count($hobbies) - 1; $i++) {
            $hobby = new Hobby();
            $hobby->setDesignation($faker->randomElement($hobbies));

            $manager->persist($hobby);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
