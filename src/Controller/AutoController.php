<?php

namespace App\Controller;

use App\Form\VehiculeType;
use App\Service\PiecesAuto;
use App\Repository\StatusRepository;
use App\Repository\VehiculeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AutoController extends AbstractController
{
    public function __construct(
        VehiculeRepository $vehiculeRepo,
        StatusRepository $statusRepo,
        EntityManagerInterface $em
    ) {
        $this->vehiculeRepo = $vehiculeRepo;
        $this->statusRepo = $statusRepo;
        $this->em = $em;
    }

    /**
     * @Route("/", name="carIndex")
     */
    public function index(PiecesAuto $piecesAuto): Response
    {
        return $this->render('car/index.html.twig', [
            'vehicules' => $piecesAuto->handleVehicules($this->vehiculeRepo->findAll()),
            'statuses' => $this->statusRepo->findAll()
        ]);
    }

    /**
     * @Route("/creer", name="carCreate")
     */
    public function create(Request $request): Response
    {
        $form = $this->createForm(VehiculeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $vehicule = $form->getData();

            $this->em->persist($vehicule);
            $this->em->flush();

            $this->addFlash('successCreate', $vehicule->getName());

            return $this->redirectToRoute('carIndex');
        }

        return $this->render('car/create.html.twig', [
            'formView' => $form->createView()
        ]);
    }

    /**
     * @Route("/editer/{id<\d+>}", name="carUpdate")
     */
    public function update($id, Request $request): Response
    {
        $vehicule = $this->vehiculeRepo->findOneBy(['id' => $id]);

        if (!$vehicule) throw $this->createNotFoundException("Ce véhicule n'existe pas !");

        $form = $this->createForm(VehiculeType::class, $vehicule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $vehicule = $form->getData();

            $this->em->persist($vehicule);
            $this->em->flush();

            $this->addFlash('successUpdate', $vehicule->getName());

            return $this->redirectToRoute('carIndex');
        }

        return $this->render('car/update.html.twig', [
            'formView' => $form->createView(),
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

        $this->addFlash('successDelete', $vehicule->getName());

        return $this->redirectToRoute('carIndex');
    }

    /**
     * @Route("/api/changeCarStatus", name="carUpdateStatus")
     */
    public function updateStatus(Request $request): Response
    {
        $params = json_decode($request->getContent(), true);
        $vehiculeId = $params['vehiculeId'];
        $newStatusId = $params['newStatusId'];

        $status = $this->statusRepo->findOneBy(['id' => $newStatusId]);
        if (!$status) throw $this->json("Ce statut n'existe pas !", 404);

        $vehicule = $this->vehiculeRepo->findOneBy(['id' => $vehiculeId]);
        if (!$vehicule) throw $this->json("Ce véhicule n'existe pas !", 404);

        $vehicule->setStatus($status);

        $this->em->persist($vehicule);
        $this->em->flush();

        return $this->json([
            'name' => $vehicule->getName(),
            'license' => $vehicule->getLicense(),
            'status' => $vehicule->getStatus()->getDescription()
        ], 200);
    }
}
