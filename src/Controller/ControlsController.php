<?php

namespace App\Controller;

use App\Entity\Controls;
use App\Form\ControlsType;
use App\Repository\ControlsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/controls")
 */
class ControlsController extends AbstractController
{
    /**
     * @Route("/", name="app_controls_index", methods={"GET"})
     */
    public function index(ControlsRepository $controlsRepository): Response
    {
        return $this->render('controls/index.html.twig', [
            'controls' => $controlsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_controls_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ControlsRepository $controlsRepository): Response
    {
        $control = new Controls();
        $form = $this->createForm(ControlsType::class, $control);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $controlsRepository->add($control);
            return $this->redirectToRoute('app_controls_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('controls/new.html.twig', [
            'control' => $control,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_controls_show", methods={"GET"})
     */
    public function show(Controls $control): Response
    {
        return $this->render('controls/show.html.twig', [
            'control' => $control,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_controls_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Controls $control, ControlsRepository $controlsRepository): Response
    {
        $form = $this->createForm(ControlsType::class, $control);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $controlsRepository->add($control);
            return $this->redirectToRoute('app_controls_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('controls/edit.html.twig', [
            'control' => $control,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_controls_delete", methods={"POST"})
     */
    public function delete(Request $request, Controls $control, ControlsRepository $controlsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$control->getId(), $request->request->get('_token'))) {
            $controlsRepository->remove($control);
        }

        return $this->redirectToRoute('app_controls_index', [], Response::HTTP_SEE_OTHER);
    }
}
