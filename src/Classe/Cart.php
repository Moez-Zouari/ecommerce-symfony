<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class Cart
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        //Stocker dans une variable cart le panier, s'il est vide, revoi un tableau vide
        $cart = $this->session->get('cart', []);
        //Si  mon carte pour un produit determiner existe deja, il faut l'incrémenter
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->session->set('cart', $cart);
    }


    public function get()
    {
        return $this->session->get('cart');
    }


    public function remove()
    {
        return $this->session->remove('cart');
    }


    public function delete($id)
    {
        //je recupére le contenu du carte
        $cart = $this->session->get('cart', []);
        //Je retire le produit de ma carte par li biais d'id demandé
        unset($cart[$id]);
        //Set a nouveau le panier
        return  $this->session->set('cart', $cart);
    }


    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);
        //Verifier si la quantité de notre produit = 1
        if ($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }
        return  $this->session->set('cart', $cart);
    }


    public function getFull()
    {
        /* 
        A chaque produit je voudrais enrichir ce carte complete de data de nos produit
        que doctrine va nous chercher dans la bd
        */
        $cartComplete = [];

        if ($this->get()) {
            // Pour dire que Id c'est ma clé et quantity est ma valeur
            foreach ($this->get() as $id => $quantity) {
                $product_object =  $this->entityManager->getRepository(Product::class)->findOneById($id);
                //Supprimer l'objet si il ne le trouve pas ou inexistant de lpuis ma carte pour eviter les erreurs
                if (!$product_object) {
                    $this->delete($id);
                    //Sort du boucle et tu passes au produit suivant
                    continue;
                }
                /* A  chaque fois qu'il entre dans foreach, il veut qu'il injecte dans cartecomplete 
                 une nouvelle entrer */
                $cartComplete[] = [
                /* Get le produit par id a traver doctriine qui va utiliser notre entité manager 
                et qui va aller chercher en utilisant les requete predefeni grace a symfony */
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }
        return   $cartComplete;
    }

    
}
