<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route("/home", name: "home")]
    function index(Request $request): Response {
        // dump($request);
        // return new Response('Hello');
    // Maniere d'obtenir le parametre de l'URL ('http://localhost:8000/?city=Paris)
        // return new Response('Hello' . ' ' . $_GET['city']);
    // Autre maniere d'obtenir le parametre de l'URL ('http://localhost:8000/?city=Paris) avec une valeur par default
        // return new Response('Hello' . ' ' . $request->query->get('city', 'Ra'));

        return $this->render('home/index.html.twig');
    }
}
