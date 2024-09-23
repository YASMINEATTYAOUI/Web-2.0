<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    /*
    #[Route('/home', name: 'app_home')] //
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/HomeController.php',
        ]);
    }
    */

    #[Route('/home', name: 'app_home')] //
    public function index(): Response
    {
        $name="Yasmine";
        return $this->render('home/home.html.twig',array(
        'name'=>$name
    ));
    }


    #[Route('/contact', name: 'app_contact')] //
    public function contact(): Response
    {
        $name="Yasmine";
        return $this->render('home/contact.html.twig',array(
            'name'=>$name
        ));
    }

}
