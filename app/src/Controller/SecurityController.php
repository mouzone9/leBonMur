<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Advertisement;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\UserRepository;
use App\Repository\AdvertisementRepository;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/vote/{adSlug}/{userId}", name="vote_user", methods={"POST"})
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param User $users
     * @param Advertisement $ad
     * @param UserRepository $userRepo
     * @param AdvertisementRepository $adRepo
     * @return RedirectResponse
     */
    public function getVote($adSlug, $userId, UserRepository $userRepo, AdvertisementRepository $adRepo, EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        $vote = $request->request->get('vote');
        $users = $userRepo->findOneById($userId);

        if ($vote === "up") {
            $users->upvote();
        } else {
            $users->downVote();
        }

        $entityManager->flush();

        return $this->redirectToRoute('ad', [
            'slug' => $adSlug
        ]);
    }
}
