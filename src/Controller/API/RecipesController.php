<?php

namespace App\Controller\API;

use App\DTO\PaginationDTO;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Instanceof_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RecipesController extends AbstractController
{

    #[Route("/api/recettes", methods: ["GET"])]
    public function index(RecipeRepository $repository, Request $request, SerializerInterface $serializer, #[MapQueryString] ?PaginationDTO $paginationDTO = null)
    {
        // $recipes = $repository->findAll();
        // $recipes = $repository->paginateRecipes($request->query->getInt('page', 1));
        $recipes = $repository->paginateRecipes($paginationDTO?->page); //avec query parameter ?page=int dans l'url sinon erreur
        // dd($serializer->serialize($recipes, 'yaml', [
        //     'groups' => ['recipes.index']
        // ]));

        return $this->json(
            $recipes,
            200,
            [],
            [
                'groups' => ['recipes.index']
            ]
        );
    }

    #[Route("/api/recettes/{id}", requirements: ['id' => Requirement::DIGITS])]
    public function show(Recipe $recipe)
    {
        return $this->json($recipe, 200, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }

    #[Route("/api/recettes", methods: ["POST"])]
    // public function create(Request $request, SerializerInterface $serializer)
    // {
    // //    dd($serializer->deserialize($request->getContent(), Recipe::class, 'json'));
    //     $recipe = new Recipe();
    //     $recipe->setCreatedAt(new \DateTimeImmutable());
    //     $recipe->setUpdatedAt(new \DateTimeImmutable());
    //     dd($serializer->deserialize($request->getContent(), Recipe::class, 'json', [
    //         AbstractNormalizer::OBJECT_TO_POPULATE => $recipe,
    //         'groups' => ['recipes.create']
    //     ]));
    // }
    // Object Recipe créé automatiquement à partir des données provenant de l'API (postman json methods POST)
    public function create(#[MapRequestPayload(serializationContext: ['groups' => ['recipes.create']])] Recipe $recipe, EntityManagerInterface $em)
    {
        $recipe->setCreatedAt(new \DateTimeImmutable());
        $recipe->setUpdatedAt(new \DateTimeImmutable());
        $em->persist($recipe);
        $em->flush();
        return $this->json($recipe, 200, [], [
            'groups' => ['recipes.index', 'recipes.show']
        ]);
    }
}
