<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * @Route("/search", name="product_search")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function search(Request $request, EntityManagerInterface $entityManager): Response
    {
        $searchTerm = $request->query->get('q');
    
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('p')
            ->from(Product::class, 'p')
            ->where($queryBuilder->expr()->like('p.name', ':searchTerm'))
            ->setParameter('searchTerm', '%' . $searchTerm . '%');
        
        $query = $queryBuilder->getQuery();
        $products = $query->getResult();

        return $this->render('shop/search.html.twig', [
            'products' => $products,
            'searchTerm' => $searchTerm,
        ]);
    }
    
}
