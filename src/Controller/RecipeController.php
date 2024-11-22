<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/recettes', name: 'recipe.index')]
    public function index(Request $request, RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();
        // dd($recipes);
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['slug' => '[a-z0-9-]+', 'id' => '\d+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $repository): Response
    {
        // $recipe = $repository->findOneBy(['slug' => $slug]);
        $recipe = $repository->find($id);
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
}
