<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ManageAccountFormType;
use App\Repository\UserRepository;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
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
}
