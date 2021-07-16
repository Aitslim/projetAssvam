<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Controller\Admin\AdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function addPost(Request $request): Response
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
