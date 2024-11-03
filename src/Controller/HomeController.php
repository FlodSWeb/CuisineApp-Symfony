<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route("/", name: "home")]
    function index (Request $request) : Response {
        // dd($request);
        // return new Response('WOLOLO');
    // Maniere d'obtenir le parametre de l'URL ('http://localhost:8000/?city=Paris)
        // return new Response('WOLOLO' . ' ' . $_GET['city']);
    // Autre maniere d'obtenir le parametre de l'URL ('http://localhost:8000/?city=Paris) avec une valeur par default
        return new Response('WOLOLO' . ' ' . $request->query->get('city', 'Ramses'));
    }
}
