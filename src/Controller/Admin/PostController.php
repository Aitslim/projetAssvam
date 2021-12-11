<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Service\FileUploader;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/admin/post", name="admin_post_list")
     */
    public function indexPost(Request $request, PostRepository $postRepository, PaginatorInterface $paginator): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $keysearch = $request->request->get('keytitle');

        if (isset($keysearch) && $keysearch !== '') {
            $posts = $postRepository->findPostsByTitle($keysearch);
        } else {
            // NOTE : Prévoir la pagination
            $posts = $postRepository->findAdminPosts();
        }

        if (!$posts) {
            $this->addFlash('notfound', 'Aucun Article trouvé.');
        }

        // Pagination
        $pagination = $paginator->paginate(
            $posts, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        // Fin Pagination

        return $this->render('admin/post/list.html.twig', [
            'posts' => $pagination
        ]);
    }

    /**
     * @Route("/admin/post/add", name="admin_post_add")
     */
    public function addPost(Request $request, FileUploader $fileUploader): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$post->getContent()) {
                $this->addFlash('error', 'Contenu est requis pour ajouter un article !');
                return $this->redirectToRoute('admin_post_add');
            }

            $post->setUser($this->getUser());
            $post->setActive($post->getActive());

            $imageFile = $form->get('imagefilename')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $post->setImageFilename($imageFileName);
            }

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
    public function updatePost(Post $post, Request $request, FileUploader $fileUploader): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (!$post->getContent()) {
                $this->addFlash('error', 'Contenu est requis pour ajouter un article !');
                return $this->redirectToRoute('admin_post_update');
            }

            $newImageFile = $form->get('imagefilename')->getData();
            if ($newImageFile) {

                if ($post->getImageFilename()) {
                    $oldImageFile = $fileUploader->getTargetDirectory() . '/' . $post->getImageFilename();
                    $fs = new Filesystem();
                    $fs->remove($oldImageFile);
                }

                $post->setImageFilename($fileUploader->upload($newImageFile));
            }

            $post->setActive($post->getActive());
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Article modifié avec succès !');
            return $this->redirectToRoute('admin_post_list');
        }

        return $this->render('admin/post/update.html.twig', [
            'currentimgpost' => $post->getImageFilename(), // renvoi image courante
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/post/activate/{id}", name="admin_post_activate", requirements={"id"="\d+"})
     */
    public function activatePost(Post $post): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $post->setActive(($post->getActive()) ? false : true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($post);
        $em->flush();

        return $this->redirectToRoute('admin_post_list');
    }

    /**
     * @Route("/admin/post/delete/{id}", name="admin_post_delete", requirements={"id"="\d+"})
     */
    public function deletePost(Post $post): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', 'Article supprimé !');

        return $this->redirectToRoute('admin_post_list');
    }

    /**
     * @Route("/admin/post/{id}", name="admin_post_view", requirements={"id"="\d+"})
     */
    public function viewPost($id, PostRepository $postRepository): Response
    {

        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $post = $postRepository->findOneBy(['id' => $id]);

        return $this->render('admin/post/view.html.twig', [
            'post' => $post
        ]);
    }
}
