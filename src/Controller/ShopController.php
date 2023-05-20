<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
class ShopController extends AbstractController
{
    /**
     * @Route("/shop", name="app_shop")
     */
    public function index(): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findBy([]);
        return $this->render('shop/index.html.twig', [
            'products' => $products,
        ]);
    }
}
