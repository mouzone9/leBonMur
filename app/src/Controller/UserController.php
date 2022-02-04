<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ManageAccountFormType;
use App\Repository\UserRepository;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    #[IsGranted("ROLE_ADMIN")]
    public function index(UserRepository $repository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $repository->findAll(),
        ]);
    }

    #[Route('/account', name: 'app_account')]
    #[IsGranted("ROLE_USER")]
    public function account(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ManageAccountFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            /** @var User $updatedUser */
            $updatedUser = $form->getData();
            $updatedUser->setPassword(
                $userPasswordHasher->hashPassword(
                    $updatedUser,
                    $updatedUser->getPassword()
                )
            );
            $entityManager->flush();

        }

        return $this->render('user/edit-one.html.twig', [
            'accountForm' => $form->createView(),
        ]);
    }

    #[Route('/user/edit/{id}', name: 'user_edit')]
    #[IsGranted("ROLE_ADMIN")]
    public function edit(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        User $user,
    ): Response
    {

        $form = $this->createForm(ManageAccountFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            /** @var User $updatedUser */
            $updatedUser = $form->getData();
            $updatedUser->setPassword(
                $userPasswordHasher->hashPassword(
                    $updatedUser,
                    $updatedUser->getPassword()
                )
            );
            $entityManager->flush();

        }

        return $this->render('user/edit-one.html.twig', [
            'accountForm' => $form->createView(),
        ]);
    }

    #[Route('/user/delete/{id}', name: 'user_delete')]
    #[IsGranted("ROLE_ADMIN")]
    public function delete(
        EntityManagerInterface $entityManager,
        User $user,
    ): Response
    {
        $name = $user->getfirstName();
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash("success", "User $name has been deleted correctly");

        return $this->redirectToRoute("app_users");
    }
}
