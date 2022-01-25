<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Repository\AdvertisementRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AdvertisementController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(AdvertisementRepository $repository, TagRepository $tagRepository, SerializerInterface $serializer): Response
    {

        return $this->render('advertisement/index.html.twig', [
            'ads' => $repository->findAll(),
            'tags' => $tagRepository->findAll()
        ]);
    }
}
