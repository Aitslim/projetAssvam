<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Controller\Admin\AdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AdminController
{
    /**
     * @Route("/admin/post", name="admin_post_list")
     */
    public function indexPost(PostRepository $postRepository): Response
    {
        if (!$this->isAdmin()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('admin/post/list.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/post/add", name="admin_post_add")
     */
    public function addPost(Request $request, SluggerInterface $slugger): Response
    {
        if (!$this->isAdmin()) {
            return $this->redirectToRoute('home');
        }

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //
            $post->setUser($this->getUser());
            $post->setActive(false);
            $post->setArchived(false);
            //
            // Début image
            /** @var UploadedFile $postimageFile */
            $postimageFile = $form->get('imagefilename')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($postimageFile) {
                $originalFilename = pathinfo($postimageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $postimageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $postimageFile->move(
                        $this->getParameter('imgposts_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageFilename' property to store the PDF file name
                // instead of its contents
                $post->setImageFilename($newFilename);
            }
            // Fin image

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Article ajouté avec succès !');
            return $this->redirectToRoute('admin_post_list');
        }

        return $this->render('admin/post/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/post/update/{id}", name="admin_post_update", requirements={"id"="\d+"})
     */
    public function updatePost(Post $post, Request $request): Response
    {
        if (!$this->isAdmin()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Article modifié avec succès !');
            return $this->redirectToRoute('admin_post_list');
        }

        return $this->render('admin/post/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/post/activate/{id}", name="admin_post_activate", requirements={"id"="\d+"})
     */
    public function activatePost(Post $post): Response
    {
        if (!$this->isAdmin()) {
            return $this->redirectToRoute('home');
        }

        $post->setActive(($post->getActive()) ? 'false' : 'true');
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();
        return new Response('true');
    }

    /**
     * @Route("/admin/post/delete/{id}", name="admin_post_delete", requirements={"id"="\d+"})
     */
    public function deletePost(Post $post): Response
    {
        if (!$this->isAdmin()) {
            return $this->redirectToRoute('home');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', 'Article supprimé !');
        return $this->redirectToRoute('admin_post_list');
    }
}
