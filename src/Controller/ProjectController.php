<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
    //
    /**
     * @Route("/admin/project", name="admin_project_list")
     */
    public function indexProject(Request $request, ProjectRepository $projectRepository): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $keysearch = $request->request->get('keytitle');

        if (isset($keysearch) && $keysearch !== '') {
            $projects = $projectRepository->findProjectsByTitle($keysearch);
        } else {
            // A REVOIR : ne pas limiter. Prévoir la pagination
            $projects = $projectRepository->findAdminProjects(100);
        }

        if (!$projects) {
            $this->addFlash('NotFound', 'Aucun Projet ne correspond à votre recherche.');
        }

        return $this->render('admin/project/list.html.twig', [
            'projects' => $projects
        ]);
    }

    /**
     * @Route("/admin/project/add", name="admin_project_add")
     */
    public function addProject(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //
            $project->setUser($this->getUser());

            // $project->setActive(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this->addFlash('success', 'Projet ajouté avec succès !');
            return $this->redirectToRoute('admin_project_list');
        }

        return $this->render('admin/project/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/project/update/{id}", name="admin_project_update", requirements={"id"="\d+"})
     */
    public function updateProject(Project $project, Request $request, FileUploader $fileUploader): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            $this->addFlash('success', 'Projet modifié avec succès !');
            return $this->redirectToRoute('admin_project_list');
        }

        return $this->render('admin/project/update.html.twig', [
            'imageproject' => $project->getImageFilename(), // renvoi en cours
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/project/activate/{id}", name="admin_project_activate", requirements={"id"="\d+"})
     */
    public function activateProject(Project $project): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        // $project->setActive(($project->getActive()) ? false : true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();
        // return new Response('true');
        return $this->redirectToRoute('admin_project_list');
    }

    /**
     * @Route("/admin/project/delete/{id}", name="admin_project_delete", requirements={"id"="\d+"})
     */
    public function deleteProject(Project $project): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('home');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($project);
        $em->flush();
        $this->addFlash('success', 'Projet supprimé !');

        return $this->redirectToRoute('admin_project_list');
    }

    /**
     * @Route("/admin/project/{id}", name="admin_project_view", requirements={"id"="\d+"})
     */
    public function viewProject($id, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->findOneBy(['id' => $id]);

        return $this->render('admin/project/view.html.twig', [
            'project' => $project
        ]);
    }
    //

    /**
     * @Route("/project", name="project")
     */
    public function index(): Response
    {
        return $this->render('project/index.html.twig', [
            'controller_name' => 'ProjectController',
        ]);
    }
}
