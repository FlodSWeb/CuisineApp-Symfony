<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route("/", name: "home")]
    function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response {
        // dump($request);
        // return new Response('Hello');
    // Maniere d'obtenir le parametre de l'URL ('http://localhost:8000/?city=Paris)
        // return new Response('Hello' . ' ' . $_GET['city']);
    // Autre maniere d'obtenir le parametre de l'URL ('http://localhost:8000/?city=Paris) avec une valeur par default
        // return new Response('Hello' . ' ' . $request->query->get('city', 'Ra'));

        // AJOUT D'UN USER
        // $user = new User;
        // $user->setEmail('usermail@mail.com')->setUsername('TheUser')->setPassword($hasher->hashPassword($user, '1234'))->setRoles([]);
        // $em->persist($user);
        // $em->flush();

        return $this->render('home/index.html.twig');
    }
}
