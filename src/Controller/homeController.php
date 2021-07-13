<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class homeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        /* Rechercher les X Actualités à afficher dans l'Accueil */
        return $this->render('home/index.html.twig', [
            'controller_name' => 'homeController',
            'NomVillage' => 'NomVillage',
            'username' => 'username',
        ]);
    }


    /**
     * @Route("/actualite", name="actualite")
     */

    /*
    public function article(.............): Response
    {

        $article = ....

        return $this->render('home/article.html.twig', [
            'article' => $article,
        ]);

    }
*/
}
