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
            'letitre_accordeon1' => 'Première Alerte accordeon numero 1',
            'letitre_accordeon2' => 'Deuxième Alerte accordeon numero 2',
            'v_alert_msg' => "Information importante : mon message. Best check yo self, you're not looking too good."
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
        $post = $postRepository->findOneBy(['id' => $id]);

        return $this->render('home/view.html.twig', [
            'post' => $post,
        ]);
    }
}
