<?php

namespace App\Controller;

use App\Repository\StatusRepository;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutoController extends AbstractController
{
    public function __construct(
        VehiculeRepository $vehiculeRepo,
        StatusRepository $statusRepo,
        EntityManagerInterface $em
        )
    {
        $this->vehiculeRepo = $vehiculeRepo;
        $this->statusRepo = $statusRepo;
        $this->em = $em;
    }

    /**
     * @Route("/", name="carIndex")
     */
    public function index(): Response
    {
        return $this->render('auto/index.html.twig', [
            'vehicules' => $this->vehiculeRepo->findAll(),
            'statuses' => $this->statusRepo->findAll()
        ]);
    }

    /**
     * @Route("/creer", name="carCreate")
     */
    public function create(): Response
    {
        return $this->render('auto/create.html.twig');
    }

    /**
     * @Route("/editer/{id<\d+>}", name="carUpdate")
     */
    public function update($id): Response
    {
        $vehicule = $this->vehiculeRepo->findOneBy(['id' => $id]);

        if (!$vehicule) throw $this->createNotFoundException("Ce véhicule n'existe pas !");

        return $this->render('auto/update.html.twig', [
            'vehicule' => $vehicule
        ]);
    }

    /**
     * @Route("/supprimer/{id<\d+>}", name="carDelete")
     */
    public function delete($id): Response
    {
        $vehicule = $this->vehiculeRepo->findOneBy(['id' => $id]);

        if (!$vehicule) throw $this->createNotFoundException("Ce véhicule n'existe pas !");

        $this->em->remove($vehicule);
        $this->em->flush();

        return $this->redirectToRoute('carIndex');
    }
}
