<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Factory\AdvertisementFactory;
use App\Form\AdvertisementFormType;
use App\Repository\AdvertisementRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdvertisementController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(AdvertisementRepository $repository, TagRepository $tagRepository, SerializerInterface $serializer): Response
    {
        return $this->render('advertisement/index.html.twig', [
            'ads' => $repository->findAllByStatus(Advertisement::$DRAFT_STATUS),
            'tags' => $tagRepository->findAll()
        ]);
    }

    #[Route('/ad/create', name: 'create_ad')]
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdvertisementFormType::class, );
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $pictures = $form->get('slider_pictures')->getData();

            /** @var Advertisement $advertisement */
            $advertisement = $form->getData();
            if ($pictures) {
                $picturesPath = [];
                foreach ($pictures as $picture) {
                    $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $picture->move(
                            $this->getParameter('pictures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        $this->addFlash("error","An error occured during the upload :". $e->getMessage());
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $picturesPath[] = $newFilename;
                }
                $advertisement->setPictures($picturesPath);
            } else {
                $advertisement->setPictures([]);
            }


            $advertisement
                ->setPublicationDate(new \DateTime())
                ->setSlug($slugger->slug($advertisement->getTitle()))
                ->setStatus(Advertisement::$DRAFT_STATUS);

            $em->persist($advertisement);
            $em->flush();

            $this->addFlash("success", "Well done ! Your new advertisement is created : ". $advertisement->getTitle() );

            return $this->redirectToRoute("index");
        }
        return $this->render('advertisement/new.html.twig', [
            'adForm' => $form->createView()
        ]);
    }



    #[Route('/ad/{slug}', name: 'ad')]
    public function showOne(Advertisement $advertisement): Response
    {
        return $this->render('advertisement/show-one.html.twig', [
            'ad' => $advertisement
        ]);
    }
}
