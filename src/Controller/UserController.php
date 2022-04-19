<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     *page qui renvoie la liste des utilisateurs de la plateforme sauf l'utilisateur courant pour eviter de supprimer son propre compte en admin
     * gestion en twig de la condition
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/", name="app_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * page qui permet de creer un nouvel user avec un mot de passe provisoire "nom-prenom-neo-connexion" an lowercase
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/new", name="app_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles([$form->get('roles')->getData()]);
            $user->setPassword($userPasswordHasher->hashPassword($user,strtolower($form->get('nom')->getData())."-".strtolower($form->get('prenom')->getData())."-neo-connexion"));
            $userRepository->add($user);
            $this->addFlash('success', 'L\'utilisateur'.$user->getNom()." ".$user->getPrenom()." a bien été enregistré");
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }


    /**
     * page qui permet d'editer un user sans avoir a acceder a son mot de passe gestion nom prenom mail et niveau d'acces
     * @param Request $request
     * @param User $user
     * @param UserRepository $userRepository
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRoles([$form->get('roles')->getData()]);
            $userRepository->add($user);
            $this->addFlash('success', 'L\'utilisateur '.$user->getNom()." ".$user->getPrenom()." a bien été modifié");
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * effacer un utilisateur
     * @param Request $request
     * @param User $user
     * @param UserRepository $userRepository
     * @return Response
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route("/{id}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user);
        }
        $this->addFlash('success', 'L\'utilisateur '.$user->getNom()." ".$user->getPrenom()." a bien été supprimé");
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
