<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart 
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function add($id)
    {
        //Stocker dans une variable cart le panier, s'il est vide, revoi un tableau vide
        $cart = $this->session->get('cart',[]);

        //Si  mon carte pour un produit determiner existe deja, il faut l'incrémenter
        if(!empty($cart[$id])){
            $cart[$id]++;
        }
        else{
            $cart[$id] = 1;
        }

        $this->session->set('cart',$cart);
    }

    public function get(){
        return $this->session->get('cart');
    }
    public function remove(){
        return $this->session->remove('cart');
    }
}