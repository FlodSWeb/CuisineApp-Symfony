<?php

namespace App\Controller\API;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\SerializerInterface;

class RecipesController extends AbstractController
{
    
    #[Route("/api/recettes")]
    public function index(RecipeRepository $repository, Request $request, SerializerInterface $serializer)
    {
        // $recipes = $repository->findAll();
        $recipes = $repository->paginateRecipes($request->query->getInt('page', 1));
        // dd($serializer->serialize($recipes, 'yaml', [
        //     'groups' => ['recipes.index']
        // ]));

        return $this->json($recipes, 200, [],
            ['groups' => ['recipes.index']
        ]);
    }

    #[Route("/api/recettes/{id}", requirements: ['id' => Requirement::DIGITS])]
    public function show(Recipe $recipe)
    {
        return $this->json($recipe, 200, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }
}
