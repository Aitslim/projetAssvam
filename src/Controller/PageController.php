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
}
