<?php

namespace App\Controller;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\RouterInterface;

class CartController extends AbstractController
{

    public function verifier(){
        
    }

    /**
     * @Route("/cart", name="app_cart")
     */
    
     public function index(Request $request,ProductRepository $productRepository ,Security $security ,RouterInterface $router): Response
     {
        $user = $security->getUser();
        if (!$user) {
            $loginRoute = 'app_login';
            $redirectUrl = $router->generate($loginRoute);
            return new RedirectResponse($redirectUrl);
        }else{
            $session = $request->getSession();
            $panier = $session->get('panier',[]);
            $panierData = [];
            $total = 0;
            $totalByProduct = [];

            foreach ($panier as $id => $quantity){
                $product=$productRepository->find($id);
                $panierData[] = [
                    'produitId'=> $product->getId(),
                    'produitPrice'=> $product->getPrice(),
                    'produitName'=> $product->getName(),
                    'quantity'=> $quantity,
                    'produitPhoto'=>$product->getPhoto(),
                    'totalByProduct'=>$product->getPrice() * $quantity
                ];
                
                $total += $product->getPrice() * $quantity;
            }
            return $this->render('cart/index.html.twig', ['items'=>$panierData,'total'=>$total,'totalByProduct'=>$totalByProduct]);
        }
     }
    /**
     * @Route("/add/{id}", name="add")
     */
    public function add($id, Request $request)
    {
        $session = $request->getSession();
        $panier = $session->get('panier',[]);
        if(!empty($panier[$id]))
            $panier[$id]++;
        else
            $panier[$id]=1;
        $session->set('panier',$panier);
        return $this->redirectToRoute('cart');
    }
    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Product $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("app_cart");
    }

    /**
    * @Route("/delete/{id}", name="delete")
    */
   public function delete(Product $product, SessionInterface $session)
   {
       // On récupère le panier actuel
       $panier = $session->get("panier", []);
       $id = $product->getId();

       if(!empty($panier[$id])){
           unset($panier[$id]);
       }

       // On sauvegarde dans la session
       $session->set("panier", $panier);

       return $this->redirectToRoute("app_cart");
   }

   /**
    * @Route("/delete", name="delete_all")
    */
   public function deleteAll(SessionInterface $session)
   {
       $session->remove("panier");
        
       return $this->redirectToRoute("app_cart");
   }

}
