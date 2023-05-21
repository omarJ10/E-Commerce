<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findBy([]);

        $user = $this->getUser();
        
        return $this->render('home/index.html.twig', [
            'products' => $products,
        ]);
    }
}
