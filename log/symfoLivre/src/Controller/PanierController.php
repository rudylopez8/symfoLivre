<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\LivreRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/panier', name:'panier')]

class PanierController extends AbstractController
{ 
    
#[Route('/index', name: 'index', methods: ['GET'])]
public function index(LivreRepository $livreRepo, sessionInterface $session): Response
{
    // JE recupère mon panier
    $panier = $session->get("panier", []);
    
    // On "fabrique" les données
    $cardDatas = [];
    $total = 0;

   // je boucle sur mon panier
   // J'y recupère l'ID et la Quantité
    foreach($panier as $id => $quantity){
        
        // je recupère un article en passant par Articlerepository
        $livre = $livreRepo->find($id);

        // dans mon CardDats je fais 1 tableau ou je met le livre
        $cardDatas[] = [
            "livre" => $livre,
            "quantity" => $quantity
        ];
        
        // J'ajoute au Total sa valeur
        $total += $livre->getPrixLivre() * $quantity;
    }
    return $this->render('livre/panier.html.twig', compact("cardDatas", "total"));
        
}


#[Route('/_ajout/{id}', name:'_ajout')]
public function add(Livre $livre, SessionInterface $session)
{
#Base Symfony
    //dd($session);
   
    // Utilisation du Seteur
    //$session->set("panier", 3);
    //dd($session); 

    // Utilisation du Getter
    //$session->set("panier", 3);
    //dd($session->get("panier"));

    //$panier = $session->get("panier", []);
    //$id = $product->getId();


#Ajout d'un produit au Panier
 // On récupère l'information dans mon panier
 $panier = $session->get("panier", []);

    $id = $livre->getId();
    if(!empty($panier[$id])){
        $panier[$id]++;
    }else{
    $panier[$id] = 1;
}
   // dd($panier);
 //$id = $article->getId();

 // Je conserve ma session
 $session->set("panier", $panier);
 dd($session);

 // Je retourne sur la page panier
 return $this->redirectToRoute("card_ajout");

}
     
}


