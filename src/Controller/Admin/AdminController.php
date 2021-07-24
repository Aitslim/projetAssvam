<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function index(): Response
    {
        if (!$this->isAdmin()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    // Toutes les Routes /admin/.... passent d'abbord ici !
    protected function isAdmin()
    {
        // Si user non connecté
        if ($this->getUser()) {
            // false si user n'a pas "ROLE_ADMIN"
            return in_array("ROLE_ADMIN", $this->getUser()->getRoles());
        }
        return false;
    }
}
