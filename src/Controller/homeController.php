<?php

namespace App\Controller;

use App\Entity\Post;
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
        $lastposts = $postRepository->findLastPosts(3);
        $oldposts = $postRepository->findOldPosts();

        return $this->render('post/index.html.twig', [
            'lastposts' => $lastposts,
            'oldposts' => $oldposts,
        ]);
    }

    /**
     * @Route("/post/{slug}", name="post_view", methods={"GET"})
     */
    public function viewpost(Post $post, PostRepository $postRepository): Response
    {
        $oldposts = $postRepository->findOldPosts();

        return $this->render('home/view.html.twig', [
            'post' => $post,
            'oldposts' => $oldposts,
        ]);
    }

    /**
     * @Route("/test", name="test")
     */
    public function test(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findLastPosts();
        dd($posts);
    }
}
