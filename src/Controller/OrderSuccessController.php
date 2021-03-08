<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderSuccessController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/commande/merci/{stripeSessionId}', name: 'order_validate')]
    public function index($stripeSessionId, Cart $cart): Response
    {
        $order =$this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        if (!$order || $order->getUsero() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if (!$order->getIsPaid()) {
            //Vider la session carte
            $cart->remove();

             // Modifier le status isPaid de notre commande en mettant 1
             $order->setIsPaid(true);
             $this->entityManager->flush();

             // Envoyer un e mail a notre client pour lui confirmer sa commande
        }
       
        
        // Afficher les quelques information de la commande de l'utilisateur

        return $this->render('order_success/index.html.twig',[
            'order' => $order
        ]);
    }
}
