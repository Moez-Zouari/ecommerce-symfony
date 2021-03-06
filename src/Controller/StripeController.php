<?php

namespace App\Controller;

use App\Classe\Cart;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session', name: 'stripe_create_session')]
    public function index(Cart $cart)
    {
        $product_for_stripe = [];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        foreach ($cart->getFull() as $product) {

            //Stripe Traitement pour recupérer les donnés de chaque produit dans le panier
            $product_for_stripe[] = [

                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product['product']->getPrice(),
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'images' => [$YOUR_DOMAIN . "/uploads/" . $product['product']->getIllustration()],
                    ],
                ],
                'quantity' => $product['quantity'],

            ];
        }

        Stripe::setApiKey('sk_test_51IRyizLQiALsjQ5rm6kcEksUN9FAStqobqCKCVF39c4mMecKzCIR1sQrkYhquQc6v0Xs8TbK1dFqVLQOc9RqHV6e00vfpOztnW');
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                $product_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
