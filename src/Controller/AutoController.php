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
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class AutoController extends AbstractController
{
    public function __construct(
        VehiculeRepository $vehiculeRepo,
        StatusRepository $statusRepo,
        EntityManagerInterface $em,
        PiecesAuto $pa
    ) {
        $this->vehiculeRepo = $vehiculeRepo;
        $this->statusRepo = $statusRepo;
        $this->em = $em;
        $this->pa = $pa;
    }

    /**
     * @Route("/", name="carIndex")
     */
    public function index(CacheInterface $cache): Response
    {
        $vehicules = $this->vehiculeRepo->findAll();

        foreach ($vehicules as $v) {

            // Utilisation du cache
            $v->paLink = $cache->get('PiecesAuto-' . $v->getId(), function (ItemInterface $item) use ($v){
                
                // Expire après une semaine !
                $item->expiresAfter(604800);

                return $this->pa->generateLink($v->getLicense());
            });
        }

        return $this->render('auto/index.html.twig', [
            'vehicules' => $vehicules,
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

            return $this->redirectToRoute('carIndex');
        }

        return $this->render('auto/create&update.html.twig', [
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

            return $this->redirectToRoute('carIndex');
        }

        return $this->render('auto/create&update.html.twig', [
            'formView' => $form->createView()
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
            'message' => "Le statut du véhicule {$vehicule->getName()} a bien été modifié."
        ], 200);
    }
}
