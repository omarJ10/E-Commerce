<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="app_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_product_new", methods={"GET", "POST"})
     */
    public function new(Request $request ,ProductRepository $produitRepository,MailerService $mailerService): Response
    {        
        
        $produit = new Product();
        $form = $this->createForm(ProductType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData(); 
            //****************Manage Uploaded FileName
            $photo_prod = $form->get('photo')->getData(); 
            $originalFilename = $photo_prod->getClientOriginalName(); 
            $newFilename = $originalFilename.'-'.uniqid().'.'.$photo_prod->getClientOriginalExtension(); 
            $photo_prod->move($this->getParameter('images_directory'),$newFilename); 
            $produit->setPhoto($newFilename);
            //****************
            $entityManager = $this->getDoctrine()->getManager(); 
            $entityManager->persist($produit); 
            $entityManager->flush();

            $mailerMessage = $produit->getName() . " a ete modifier avec succés ✅";
            $mailerService->sendEmail('jalledomar2001@gmail.com',$mailerMessage);
            $produitRepository->add($produit, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_product_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $product->setPhoto(null);
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo_prod = $form->get('photo')->getData(); 
            $originalFilename = $photo_prod->getClientOriginalName(); 
            $newFilename = $originalFilename.'-'.uniqid().'.'.$photo_prod->getClientOriginalExtension(); 
            $photo_prod->move($this->getParameter('images_directory'),$newFilename); 
            $product->setPhoto($newFilename);
            //****************
            $entityManager = $this->getDoctrine()->getManager(); 
            $entityManager->persist($product); 
            $entityManager->flush(); 
            
            $productRepository->add($product, true);
            return $this->redirectToRoute('app_product_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $productRepository->remove($product, true);
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }


    
}
