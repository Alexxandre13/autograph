<?php

namespace App\Controller;

use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutoController extends AbstractController
{
    public function __construct(VehiculeRepository $vehiculeRepo)
    {
        $this->vehiculeRepo = $vehiculeRepo;
    }

    /**
     * @Route("/", name="carRead")
     */
    public function index(): Response
    {
        return $this->render('auto/index.html.twig', [
            'vehicules' => $this->vehiculeRepo->findAll()
        ]);
    }

    /**
     * @Route("/creer", name="carCreate")
     */
    public function create(): Response
    {
        return $this->render('auto/index.html.twig', [
            'vehicules' => $this->vehiculeRepo->findAll()
        ]);
    }

    /**
     * @Route("/editer", name="carUpdate")
     */
    public function update(): Response
    {
        return $this->render('auto/index.html.twig', [
            'vehicules' => $this->vehiculeRepo->findAll()
        ]);
    }

    /**
     * @Route("/supprimer", name="carDelete")
     */
    public function delete(): Response
    {
        return $this->render('auto/index.html.twig', [
            'vehicules' => $this->vehiculeRepo->findAll()
        ]);
    }
}
