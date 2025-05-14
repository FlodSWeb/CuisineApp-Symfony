<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeFixtures extends Fixture
{

    public function __construct(private readonly SluggerInterface $slugger) {}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));

        $categories = ['Plat chaud', 'Dessert', 'Entrée', 'Snack'];
        foreach ($categories as $category) {
            $category = (new Category())
                ->setName($category)
                ->setSlug($this->slugger->slug($category))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->fateTime()))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->fateTime()));
            $manager->persist($category);
        }

        for ($i = 1; $i <= 10; $i++) {
            $title = $faker->foodName();
            $recipe = (new Recipe())
                ->setTitle($title)
                ->setSlug($this->slugger->slug($title))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->fateTime()))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->fateTime()))
                ->setContent($faker->paragraphs(10, true))
                ->setDuration($faker->numberBetween(5, 60));

            $manager->persist($recipe);
        }

        $manager->flush();
    }
}
