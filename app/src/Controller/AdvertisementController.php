<?php

namespace App\Controller;

use App\Entity\Advertisement;
use App\Entity\Answers;
use App\Entity\User;
use App\Entity\Comments;
use App\Factory\AdvertisementFactory;
use App\Form\AdvertisementFormType;
use App\Form\SearchFormType;
use App\Form\SearchType;
use App\Form\CommentsFormType;
use App\Repository\AdvertisementRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
    public function index(AdvertisementRepository $repository): Response
    {
        $searchForm = $this->createForm(SearchFormType::class);
        return $this->render('advertisement/index.html.twig', [
            'ads' => $repository->findAllByStatus(Advertisement::$PUBLIC_STATUS),
            'searchForm' => $searchForm->createView()
        ]);
    }

    #[Route('/search', name: 'search_ad')]
    public function search(AdvertisementRepository $repository, Request $request): Response
    {
        $searchForm = $this->createForm(SearchFormType::class);
        $searchForm->handleRequest($request);
        if($searchForm->isSubmitted()) {
            $query = $searchForm->getData()["textSearch"];


            return $this->render('advertisement/search-result.html.twig', [
                'ads' => $repository->findAllByQuery($query),
                'searchForm' => $searchForm->createView(),
                "query" => $query
            ]);
        }
//
//        $this->addFlash("error", "Search not correct");
//        return $this->redirectToRoute("index");

    }

    #[Route('/ad/create', name: 'create_ad')]
    #[IsGranted("ROLE_USER")]
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AdvertisementFormType::class,);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pictures = $form->get('slider_pictures')->getData();

            /** @var Advertisement $advertisement */
            $advertisement = $form->getData();
            $user = $this->getUser();
            $advertisement->setSeller($user);

            if ($pictures) {
                $picturesPath = [];
                foreach ($pictures as $picture) {
                    $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $picture->move(
                            $this->getParameter('pictures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        $this->addFlash("error", "An error occured during the upload :" . $e->getMessage());
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
                ->setSlug($slugger->slug($advertisement->getTitle()));

            $em->persist($advertisement);
            $em->flush();

            $this->addFlash("success", "Well done ! Your new advertisement is created : " . $advertisement->getTitle());

            return $this->redirectToRoute("user_ads");
        }
        return $this->render('advertisement/new.html.twig', [
            'adForm' => $form->createView()
        ]);
    }

    #[Route('/ad/edit/{slug}', name: 'update_ad')]
    #[IsGranted("ROLE_USER")]
    public function update(
        Advertisement          $advertisement,
        Request                $request,
        SluggerInterface       $slugger,
        EntityManagerInterface $em
    ): Response
    {
        $form = $this->createForm(AdvertisementFormType::class, $advertisement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pictures = $form->get('slider_pictures')->getData();

            /** @var Advertisement $advertisement */
            $advertisement = $form->getData();

            if ($pictures) {
                $picturesPath = [];
                foreach ($pictures as $picture) {
                    $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $picture->move(
                            $this->getParameter('pictures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                        $this->addFlash("error", "An error occured during the upload :" . $e->getMessage());
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $picturesPath[] = $newFilename;
                }
                $advertisement->setPictures($picturesPath);
            }


            $advertisement
                ->setSlug($slugger->slug($advertisement->getTitle()));

            $em->flush();

            $this->addFlash("success", "Well done ! Your advertisement is updated : " . $advertisement->getTitle());

            return $this->redirectToRoute("ad", ["slug" => $advertisement->getSlug()]);
        }
        return $this->render('advertisement/update.html.twig', [
            'adForm' => $form->createView(),
            'ad' => $advertisement
        ]);
    }


    #[Route('/ad/{slug}', name: 'ad')]
    public function showOne(Advertisement $advertisement, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CommentsFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comments = $form->getData();

            $user = $this->getUser();

            if($user){
                $comments->setCreatedAt(new \DateTime())
                    ->setAuthorId($user)
                    ->setAdvertisementId($advertisement);

                $em->persist($comments);
                $em->flush();

                $this->addFlash("success", 'Your comments was send ' . $comments->getText());
            } else {
                $this->addFlash("error", 'You need to connect to send a comment !');
            };
        }

        return $this->render('advertisement/show-one.html.twig', [
            'ad' => $advertisement,
            'commentForm' => $form->createView()
        ]);
    }

    #[Route('/ad/delete/{slug}', name: 'delete_ad')]
    public function delete(Advertisement $advertisement, EntityManagerInterface $em): Response
    {
        $em->remove($advertisement);
        $em->flush();
        $this->addFlash("success", "Ad" . $advertisement->getTitle() . " has successfully been deleted");
        return $this->redirectToRoute("user_ads");
    }

    #[Route('/user/ads', name: 'user_ads')]
    public function showUserAds(AdvertisementRepository $advertisementRepository): Response
    {
        return $this->render('advertisement/own-ads.html.twig', [
            'ads' => $advertisementRepository->findAllByUser($this->getUser())
        ]);
    }

    #[Route('/ad/comments/{id}/newAnswer', name : 'newAnswer')]
    public function addNewAnswer(Comments $comments, Request $request, EntityManagerInterface $em): Response
    {
        $form = $request->request->get('text');

        if ($form) {
            $answers = new Answers();

            $user = $this->getUser();

            if($user){
                $answers->setCreateAt(new \DateTime())
                    ->setAuthorId($user)
                    ->setCommentsId($comments)
                    ->setText($form);

                $em->persist($answers);
                $em->flush();

                $this->addFlash("success", 'Your answer was send ' . $answers->getText());
            } else {
                $this->addFlash("error", 'You need to connect to send an answer !');
            };
        }
        $advertisement = $comments->getAdvertisementId();

        return $this->redirectToRoute("ad", ["slug" => $advertisement->getSlug()]);
    }
}
