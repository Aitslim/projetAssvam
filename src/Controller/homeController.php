<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class homeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(PostRepository $postRepository): Response
    {
        $lastposts = $postRepository->findLastPosts();
        $oldposts = $postRepository->findOldPosts();

        return $this->render('home/index.html.twig', [
            'lastposts' => $lastposts,
            'oldposts' => $oldposts,
        ]);
    }

    // /**
    //  * --@Route("/post/view/{slug}", name="home_post_view")
    //  */
    // public function viewpostBis(Post $post, PostRepository $postRepository): Response
    // {
    //     $lastposts = $postRepository->findOneBy();
    //     $oldposts = $postRepository->findOldPosts();

    //     return $this->render('home/index.html.twig', [
    //         'lastposts' => $lastposts,
    //         'oldposts' => $oldposts,
    //     ]);
    // }

    /**
     * @Route("/post/view/{id}", name="home_post_view", requirements={"id"="\d+"})
     */
    public function viewpost($id, PostRepository $postRepository): Response
    {
        $lastposts = $postRepository->findBy(['id' => $id]);
        $oldposts = $postRepository->findOldPosts();

        return $this->render('home/index.html.twig', [
            'lastposts' => $lastposts,
            'oldposts' => $oldposts,
        ]);
    }
}
