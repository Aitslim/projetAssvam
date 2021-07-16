<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Controller\Admin\AdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AdminController
{
    /**
     * @Route("/admin/category/", name="admin_category_list")
     */
    public function indexCategory(CategoryRepository $categoryRepository): Response
    {
        if (!$this->isAdmin()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('admin/category/list.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/category/add", name="admin_category_add")
     */
    public function addCategory(Request $request): Response
    {
        if (!$this->isAdmin()) {
            return $this->redirectToRoute('home');
        }

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Votre Cetegorie a été ajouté avec succes !');

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/update/{id}", name="admin_category_update", requirements={"id"="\d+"})
     */
    public function updateCategory(Category $category, Request $request): Response
    {
        if (!$this->isAdmin()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Votre Cetegorie a été modifié avec succes !');

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin/category/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
