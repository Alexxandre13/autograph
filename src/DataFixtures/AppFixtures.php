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

        $cats = ['Neuf', 'Occasion', 'Epave'];

        foreach ($cats as $cat) {
            $s = (new Status)->setDescription($cat);
            $manager->persist($s);
        }
        $manager->flush();

        $vehicules = [
            'FL-521-RX' => 'Renault Kangoo',
            'BE-967-WH' => 'Peugeot 207',
            'FD-897-SP' => 'Audi A3',
            'CT-725-EL' => 'Opel Adam',
            'FY-839-HT' => 'Citroën C2',
            'FW-078-EX' => 'Peugeot 308',
            'GA-312-ZV' => 'Peugeot 208',
            'EE-165-HP' => 'Renault Clio',
            'BV-243-YZ' => 'Renault Scenic'
        ];

        $statusType = $this->statusRepository->findAll();

        foreach ($vehicules as $license => $name) {
            $v = (new Vehicule)
                ->setName($name)
                ->setLicense($license)
                ->setStatus($faker->randomElement($statusType));
            $manager->persist($v);
        }
        $manager->flush();
    }
}
