<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagFormType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TagController extends AbstractController
{
    #[Route('/tags', name: 'tags')]
    public function index(TagRepository $tagRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TagFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Tag $tag */
            $tag = $form->getData();

            $pictogram = $form->get("pictogram")->getData();
            if ($pictogram) {
                $originalFilename = pathinfo($pictogram->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictogram->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $pictogram->move(
                        $this->getParameter('pictograms_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $tag->setPictogram($newFilename);
            }

            $em->persist($tag);
            $em->flush();

        }

        return $this->render('tag/index.html.twig', [
            'tags' => $tagRepository->findAll(),
            'createForm' => $form->createView()
        ]);
    }


    #[Route('/tag/edit/{id}', name: 'tag_edit')]
    public function update(Tag $tag, EntityManagerInterface $em, Request $request, SluggerInterface $slugger): Response
    {
        $tagForm = $this->createForm(TagFormType::class, $tag);

        $tagForm->handleRequest($request);
        if ($tagForm->isSubmitted() && $tagForm->isValid()) {
            /**@var Tag $tag */
            $tag = $tagForm->getData();

            $pictogram = $tagForm->get("pictogram")->getData();
            if ($pictogram) {
                $originalFilename = pathinfo($pictogram->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictogram->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $pictogram->move(
                        $this->getParameter('pictograms_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $tag->setPictogram($newFilename);
            }

            $em->flush();
            $this->addFlash("success","The tag " . $tag -> getName() . " has been successfully updated");
            return $this->redirectToRoute("tags");
        }

        return $this->render('tag/update.html.twig', [
            'tag' => $tag,
            'tagForm' => $tagForm->createView(),
        ]);
    }

    #[Route('/tag/delete/{id}', name: 'tag_delete')]
    public function delete(Tag $tag, EntityManagerInterface $em): Response
    {
        $em->remove($tag);
        $em->flush();
        $this->addFlash("success","The tag " . $tag -> getName() . " has been successfully deleted");
        return $this->redirectToRoute("tags");
    }
}
