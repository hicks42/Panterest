<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home",methods="GET")
     */
    public function index(PinRepository $repo): Response
    {
        return $this->render('pins/index.html.twig', ['pins' => $repo->findall()]);
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET", "POST"})
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $pin = new Pin;

        $form = $this->createForm(PinType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($pin);
            $em->flush();

            return $this->redirectToRoute('app_pins_show', ['id' => $pin->getId()]);
        }
        return $this->render('pins/create.html.twig', [
            'monFormulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<\d+>}", name="app_pins_show", methods="GET")
     */
    public function show(Pin $pin): Response
    {
        // $pin = $repo->find($id);
        // if (!$pin) {
        //     throw $this->createNotFoundException('Pin N°' . $id . ' not found');
        // }
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/edit/{id<\d+>}", name="app_pins_edit", methods={"GET", "PUT"})
     */
    public function edit(Pin $pin, Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(PinType::class, $pin, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('pins/edit.html.twig', [
            'pin' => $pin,
            'monFormulaire' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/delete/{id<\d+>}", name="app_pins_delete", methods={"DELETE"})
     */
    public function delete(Pin $pin, EntityManagerInterface $em): Response
    {
        $em->remove($pin);
        $em->flush();
        return $this->redirectToRoute('app_home');
    }
}
