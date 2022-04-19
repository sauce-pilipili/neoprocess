<?php

namespace App\Controller;

use App\Entity\Controls;
use App\Entity\Dossiers;
use App\Form\DossiersEditType;
use App\Form\DossiersType;
use App\Repository\ControlsRepository;
use App\Repository\DocumentsRepository;
use App\Repository\DossiersRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dossiers")
 */
class DossiersController extends AbstractController
{
    /**
     * @Route("/", name="app_dossiers_index", methods={"GET"})
     */
    public function index(DossiersRepository $dossiersRepository): Response
    {
        return $this->render('dossiers/index.html.twig', [
            'dossiers' => $dossiersRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_dossiers_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DossiersRepository $dossiersRepository): Response
    {
        $dossier = new Dossiers();
        $form = $this->createForm(DossiersType::class, $dossier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dossiersRepository->add($dossier);
            return $this->redirectToRoute('app_dossiers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dossiers/new.html.twig', [
            'dossier' => $dossier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_dossiers_show", methods={"GET"})
     */
    public function show(Dossiers $dossier): Response
    {
        return $this->render('dossiers/show.html.twig', [
            'dossier' => $dossier,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_dossiers_edit", methods={"GET", "POST"})
     */
    public function edit(Request             $request,
                         Dossiers            $dossier,
                         ControlsRepository  $controlsRepository,
                         DocumentsRepository $documentsRepository,
                         DossiersRepository  $dossiersRepository)
    {
        //Trouver le type controle qui va avec le dossier
        $control = $controlsRepository->find($dossier->getPieces());
        // Trouver les documents qui sont neccessaires
        /** @var Controls $document */
        $document = $controlsRepository->findDocumentAFournir($control);
        //*****************************************************admin test
        if ($request->isXmlHttpRequest()) {
            if ($request->request->get('data')) {
                $dossier->{'setValid' . ucwords($request->request->get('index'))}(1);
            } else {
                $dossier->{'setValid' . ucwords($request->request->get('index'))}(0);
            }
            $dossier->{'setValid' . ucwords($request->request->get('index'))}($request->request->get('data'));
            try {
                $dossiersRepository->add($dossier);
            } catch (OptimisticLockException | ORMException $e) {
            }
            return new JsonResponse([
//                'responsedossiereditc' =>   $dossier->{'getValid' . ucwords($request->request->get('index'))}()
                'dump' => $dossier->{'getValid' . ucwords($request->request->get('index'))}()
            ]);
        }
        //****************************************** test
        //Creer le formulaire avec les input correspondant au nombre de document necessaire pour le dossier
        $form = $this->createForm(DossiersEditType::class, $dossier, ['attr' => [count($document[0]->getDocuments())]]);
        $form->handleRequest($request);
//        dd($document[0]->getDocuments()[0]->getName());
        if ($form->isSubmitted() && $form->isValid()) {
            for ($i = 0; $i < count($document[0]->getDocuments()); $i++) {
                //recuperer le fichier
                if ($form->get("piece_$i")->getData() != null) {
                    $fichier = $form->get("piece_$i")->getData();
                    //set le nom du fichier crée avec nom, prenom, nom du fichier, id et code unique . extension
                    $nomDuFichier = $document[0]->getDocuments()[$i]->getName() . '-' .
                        $form->get('nom')->getData() . '-' .
                        $form->get('prenom')->getData() . '-' .
                        $dossier->getId() . '.' .
                        $fichier->guessExtension();
                    //ajouter le fichier dans le serveur
                    $fichier->move(
                        $this->getParameter('file_directory'),
                        $nomDuFichier
                    );
                    // appel dynamique du setter pour le nom du fichier;
                    $dossier->{'setPiece' . ucwords($i)}($nomDuFichier);
                }
                //recuperer le nom de la piece numero $i du dossier
            }
            $dossiersRepository->add($dossier);
            return $this->redirectToRoute('app_dossiers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('dossiers/edit.html.twig', [
            //Le nom des documents a fournir
            'pieces' => $document[0]->getDocuments(),
            'dossier' => $dossier,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_dossiers_delete", methods={"POST"})
     */
    public function delete(Request $request, Dossiers $dossier, DossiersRepository $dossiersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $dossier->getId(), $request->request->get('_token'))) {
            for ($i = 0; $i < 2; $i++) {
                if ($dossier->{'getPiece' . ucwords($i)}() != null) {
                    unlink($this->getParameter('file_directory') . '/' . $dossier->{'getPiece' . ucwords($i)}());
                }
            }
            $dossiersRepository->remove($dossier);
        }
        return $this->redirectToRoute('app_dossiers_index', [], Response::HTTP_SEE_OTHER);
    }
}
