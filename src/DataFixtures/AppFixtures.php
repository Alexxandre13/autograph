<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Status;
use App\Entity\Vehicule;
use App\Repository\StatusRepository;
use App\Repository\VehiculeRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function __construct(VehiculeRepository $vehiculeRepository, StatusRepository $statusRepository)
    {
        $this->vehiculeRepository = $vehiculeRepository;
        $this->statusRepository = $statusRepository;
    }

    private function randomLicense(): string
    {
        $faker = Factory::create('fr_FR');
        return ($faker->randomLetter()
            . $faker->randomLetter()
            . '-'
            . $faker->numberBetween(100, 999)
            . '-' . $faker->randomLetter()
            . $faker->randomLetter());
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $cat1 = (new Status())->setDescription('Neuf');
        $cat2 = (new Status())->setDescription('Occasion');
        $cat3 = (new Status())->setDescription('Epave');

        $manager->persist($cat1);
        $manager->persist($cat2);
        $manager->persist($cat3);
        $manager->flush();

        $statusType = $this->statusRepository->findAll();

        $v = new Vehicule;
        $v->setName('Seat Ibiza')
            ->setLicense($this->randomLicense())
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Renault Scenic')
            ->setLicense($this->randomLicense())
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Fiat Panda')
            ->setLicense($this->randomLicense())
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Peugeot 807')
            ->setLicense($this->randomLicense())
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Renault Espace')
            ->setLicense($this->randomLicense())
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Audi A3')
            ->setLicense($this->randomLicense())
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Volkswagen Touran')
            ->setLicense($this->randomLicense())
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('BMW X3')
            ->setLicense($this->randomLicense())
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Citroën C3')
            ->setLicense($this->randomLicense())
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Mercedes Class B')
            ->setLicense($this->randomLicense())
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $manager->flush();
    }
}
