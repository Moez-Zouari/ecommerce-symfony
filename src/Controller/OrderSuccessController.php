<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
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
             $mail  = new Mail();
             $content = "Bonjour ".$order->getUsero()->getFirstname()."<br/>Merci pour votre commande.<br></br/>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book." ;
             $mail->send($order->getUsero()->getEmail(),$order->getUsero()->getFirstname(),'Votre commande Feelcrafts est bien validée', $content);
            
        }
       
        
        // Afficher les quelques information de la commande de l'utilisateur

        return $this->render('order_success/index.html.twig',[
            'order' => $order
        ]);
    }
}
