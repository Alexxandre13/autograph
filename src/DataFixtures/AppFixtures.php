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
        $v->setName('Renault Kangoo')
            ->setLicense('FL-521-RX')
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Peugeot 207')
            ->setLicense('BE-967-WH')
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Audi A3')
            ->setLicense('FD-897-SP')
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Opel Adam')
            ->setLicense('CT-725-EL')
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Citroën C2')
            ->setLicense('FY-839-HT')
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Peugeot 308')
            ->setLicense('FW-078-EX')
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Peugeot 208')
            ->setLicense('GA-312-ZV')
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Renault Clio')
            ->setLicense('EE-165-HP')
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $v = new Vehicule;
        $v->setName('Renault Scenic')
            ->setLicense('BV-243-YZ')
            ->setStatus($faker->randomElement($statusType));
        $manager->persist($v);

        $manager->flush();
    }
}
