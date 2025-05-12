<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/category', name: 'admin.category.')]
#[IsGranted('ROLE_USER')]
class CategoryController extends AbstractController {

    #[Route('/', name: 'index')]
    public function index(CategoryRepository $repository)
    {
        // dd($repository->findAllWithCount());
        return $this->render('admin/category/index.html.twig', [
            'categories' => $repository->findAllWithCount()
        ]);
    }
    
    
    #[Route('/new', name: 'new')]
    public function create(Request $request, EntityManagerInterface $em, CategoryRepository $repository)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'La catégorie a bien ajoutée.');

            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/category/new.html.twig', [
            'form' => $form->createView(),
            'categories' => $repository->findAll()
        ]);
    }
    
    
    #[Route('/{id}', name: 'edit', requirements: ['id' => Requirement::DIGITS], methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $em, CategoryRepository $repository)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La catégorie a bien été modifiée.');

            return $this->redirectToRoute('admin.category.index');
        }
        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'categories' => $repository->findAll(),
            'form' => $form->createView()
        ]);
    }
    
    
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => Requirement::DIGITS])]
    public function remove(Category $category, EntityManagerInterface $em)
    {
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'La catégorie a bien été supprimée.');

        return $this->redirectToRoute('admin.category.index');
    }

}
