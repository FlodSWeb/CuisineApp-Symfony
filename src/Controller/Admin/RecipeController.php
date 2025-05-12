<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("/admin/recettes", name: 'admin.recipe.')]
#[IsGranted('ROLE_USER')]
class RecipeController extends AbstractController
{
    #[Route('/', name: 'index')]
    // public function index(RecipeRepository $recipeRepository, CategoryRepository $categoryRepository, EntityManagerInterface $em): Response
    // {
    //     // $this->denyAccessUnlessGranted('ROLE_USER'); géré par #[IsGranted('ROLE_USER')]
    //     $recipes = $recipeRepository->findAll();
    //     // $recipes[5]->getCategory()->getName();
    //     // dd($recipes[5]->getCategory());
    //     return $this->render('admin/recipe/index.html.twig', [
    //         'recipes' => $recipes
    //     ]);
    // }
// Pagination
// public function index(RecipeRepository $recipeRepository, Request $request): Response
// {
//     $page = $request->query->getInt('page', 1);
//     $limit = 3;
//     $recipes = $recipeRepository->paginateRecipes($page, $limit);
//     // dd($recipes->count());
//     $maxPage = ceil($recipes->count() / $limit);
//     return $this->render('admin/recipe/index.html.twig', [
//         'recipes' => $recipes,
//         'maxPage' => $maxPage,
//         'page' => $page
//     ]);
// }
//KNP PAGINATOR
public function index(RecipeRepository $recipeRepository, Request $request): Response
{
    $page = $request->query->getInt('page', 1);
    $recipes = $recipeRepository->paginateRecipes($page);
    return $this->render('admin/recipe/index.html.twig', [
        'recipes' => $recipes,
    ]);
}

    #[Route('/new', name: 'new')]
    public function create(Request $request, EntityManagerInterface $em) {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isvalid()) {
            // Parametrage du slug et des DateTimesdans RecipeType.php
            // $recipe->setCreatedAt(new \DateTimeImmutable());
            // $recipe->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été ajoutée.');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/new.html.twig', [
            'form' => $form
        ]);
    }

    // supprimee ("Commit Category")
    #[Route('/{slug}-{id}', name: 'show', requirements: ['slug' => '[A-Za-z0-9-]+', 'id' => '\d+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->findOneBy(['slug' => $slug]);
        // $recipe = $recipeRepository->find($id);
        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('admin.recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }

        return $this->render('admin/recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }

        // URL : http://localhost:8000/recette/hamburger-bacon-37
        // dd($request);
        // dd($request->attributes->get('slug'), $request->attributes->getInt('id'));
        // dd($slug, $id);

        // return new Response('Recette ' . $slug);
        
        // format JSON
        // return new JsonResponse([
        //      $this->json(['slug' => $slug]),
        //     'slug' => $slug
        // ]);
    // }
       

    #[Route('/{id}', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em) {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Sans VichUploaderBundle
            // /** @var UploadedFile $file */
            // $file = $form->get('thumbnailFile')->getData();
            // $fileName = $recipe->getSlug() . '.' . $recipe->getId() . '.' . $file->getClientOriginalExtension();
            // $file->move($this->getParameter('kernel.project_dir') . '/public/recipes/images', $fileName);
            // $recipe->setThumbnail($fileName);
            // $em->flush();
            
            $em->flush();
            
            $this->addFlash('success', 'La recett e a bien été mise à jour.');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['POST'], requirements: ['id' => Requirement::DIGITS])]
    public function remove(Recipe $recipe, EntityManagerInterface $em) {
        // dd($recipe);
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a bien été supprimée.');
        return $this->redirectToRoute('admin.recipe.index');
    }
}
