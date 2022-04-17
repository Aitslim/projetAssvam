<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/about", name="about")
     */
    public function about(): Response
    {
        return $this->render('page/about.html.twig', [
            'bg_image' => "about-bg.jpg",
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', [
            'bg_image' => "contact-bg.jpg",
        ]);
    }

    /**
     * @Route("/album", name="album")
     */
    public function album(): Response
    {
        return $this->render('page/album.html.twig', [
            'img_album1' => "img_album1.jpg",
            'img_album2' => "img_album2.jpg",
            'img_album3' => "img_album3.jpg",
            'img_album4' => "img_album4.jpg",
            'img_album5' => "img_album5.jpg",
            'img_album6' => "img_album6.jpg",
            'img_album7' => "img_album7.jpg",
            'img_album8' => "img_album8.jpg",
        ]);
    }
}
