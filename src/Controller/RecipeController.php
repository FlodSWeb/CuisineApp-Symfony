<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
    #[Route('/recette', name: 'recipe.index')]
    public function index(Request $request): Response
    {
        return $this->render('recipe/index.html.twig');
    }

    #[Route('/recette/{slug}-{id}', name: 'recipe.show', requirements: ['slug' => '[a-z0-9-]+', 'id' => '\d+'])]
    public function show(Request $request, string $slug, int $id): Response
    {
        // URL : http://localhost:8000/recette/hamburger-bacon-37
        // dd($request);
        // dd($request->attributes->get('slug'), $request->attributes->getInt('id'));
        // dd($slug, $id);

        // return new Response('Recette ' . $slug);
        
        // format JSON
        // return new JsonResponse([
            // ou $this->json(['slug' => $slug])
            // 'slug' => $slug
        // ]);
        return $this->render('recipe/index.html.twig', [
            'slug' => $slug,
            'id' => $id
        ]);

    }
}
