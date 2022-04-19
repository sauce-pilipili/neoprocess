<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserPasswordType;
use App\Form\UserProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Gestion principale de l'arrivé sur le backoffice et la gestion des informations de l'utilisateur de la plateforme
 */
class AdminController extends AbstractController
{
    /**
     * route principale d'arrive sur le site cote backoffice
     * aucun parametre blocage en login sur security yaml
     * @Route("/admin", name="admin_index")
     */
    public function index(Request $request,UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher): Response
    {
if ($request->isMethod("POST")){
    dd($request->get('text'));
}
        return $this->render('admin/index.html.twig', [
        ]);
    }

    /**
     * route qui permet de modifer son email
     * @param Request $request
     * @param User $user
     * @param UserRepository $userRepository
     * @return Response
     * @Route("/{id}/mail", name="user_profil_mail", methods={"GET","POST"})
     */
    public function profilmail(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserProfilType::class, $user);
        $form->handleRequest($request);

        if ($this->getUser()!=$user){
            $this->addFlash('danger','Vous ne pouvez pas accéder à cette ressource');
            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $userRepository->add($user);
            } catch (OptimisticLockException | ORMException $e) {
                $this->addFlash('danger', 'Une erreur c\'est produite veuillez reesayer');
                return $this->redirectToRoute('user_profil_password', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
            }
            $this->addFlash('success', 'Votre adresse email a été modifié');
            return $this->redirectToRoute('user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profil/editProfilmail.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     *route qui permet de modifier son mot de passe
     * @param Request $request
     * @param User $user
     * @param UserRepository $userRepository
     * @param UserPasswordHasherInterface $userPasswordHasherInterface
     * @return Response
     * @Route("/{id}/password", name="user_profil_password", methods={"GET","POST"})
     */
    public function profilPassword(Request $request, User $user, UserRepository $userRepository,UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $form = $this->createForm(UserPasswordType::class, $user);
        $form->handleRequest($request);

        if ($this->getUser()!=$user){
            $this->addFlash('danger','Vous ne pouvez pas accéder à cette ressource');
            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            //mot de passe actuel
            $plainPassword = $form->get('ActualPassword')->getData();
            //verification mdp actuel
            if (password_verify($plainPassword, $user->getPassword())) {
                //si ok changement de mot de passe
                if ($form->get('password')->getData() === $form->get('password2')->getData()) {
                    //changement de mot de passe
                    $user->setPassword(
                        $userPasswordHasherInterface->hashPassword(
                            $user,
                            $form->get('password')->getData()
                        )
                    );
                    try {
                        $userRepository->add($user);
                    } catch (OptimisticLockException | ORMException $e) {
                        $this->addFlash('danger', 'Une erreur c\'est produite veuillez reesayer');
                        return $this->redirectToRoute('user_profil_password', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
                    }

                    $this->addFlash('success', 'Votre mot de passe a été modifié');
                    return $this->redirectToRoute('user_profil', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
                    //mauvais mdp nouveau
                } else {
                    $this->addFlash('danger', 'les champs: "nouveau mot de passe" et "confirmation" doivent être identiques');
                    return $this->redirectToRoute('user_profil_password', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
                }
            }else {
                $this->addFlash('danger', 'Votre mot de passe actuel n\'est pas valable');
                return $this->redirectToRoute('user_profil_password', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->renderForm('profil/editProfilpassword.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * route qui permet de partir sur son profil et d'acceder a la modification email ou password
     * @param Request $request
     * @param User $user
     * @return Response
     * @Route("/{id}/profil", name="user_profil", methods={"GET","POST"})
     */
    public function profil(Request $request, User $user): Response
    {
        if ($this->getUser() !== $user){
            $this->addFlash('danger','Vous ne pouvez pas accéder à cette ressource');
            return $this->redirectToRoute('admin_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('profil/editProfil.html.twig', [
            'user' => $user,
        ]);
    }




}
