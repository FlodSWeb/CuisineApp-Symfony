<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeController extends AbstractController
{
    #[Route('/recettes', name: 'recipe.index')]
    public function index(Request $request, RecipeRepository $recipeRepository, EntityManagerInterface $em): Response
    {
        
        $recipes = $recipeRepository->findAll();
        // $recipes = $recipeRepository->findWithDurationLowerThan(10);
        // dd($recipeRepository->findTotalDuration());
        // dd($recipes);

        // Creer une recette
//         $recipe = new Recipe();
//         $recipe->setTitle('Chili con carne');
//         $recipe->setSlug('chili-con-carne');
//         $recipe->setContent('Ingrédients (pour 4 personnes) :
//         500 g de bœuf haché
//         1 boîte de 400 g de tomates ....');
//         $recipe->setCreatedAt(new \DateTimeImmutable('now'));
//         $recipe->setUpdatedAt(new \DateTimeImmutable('now'));
//         $recipe->setDuration(25);

//         $em->persist($recipe);

        // Supprimer une recette
//         $recipe = $recipes[0];
//         $em->remove($recipe);

//         $em->flush();
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['slug' => '[A-Za-z0-9-]+', 'id' => '\d+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->findOneBy(['slug' => $slug]);
        // $recipe = $recipeRepository->find($id);
        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
        }

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe
        ]);

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
       
    }

    #[Route('/recettes/{id}/edit', name: 'recipe.edit', methods: ['GET', 'POST'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em) {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $recipe->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('success', 'La recette a bien été mise à jour.');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    #[Route('/recettes/new', name: 'recipe.new')]
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
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/recettes/{id}/delete', name: 'recipe.delete', methods: ['POST'])]
    public function remove(Recipe $recipe, EntityManagerInterface $em) {
        // dd($recipe);
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'La recette a bien été supprimée.');
        return $this->redirectToRoute('recipe.index');
    }
}
