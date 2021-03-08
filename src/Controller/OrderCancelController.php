<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderCancelController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande/erreur/{stripeSessionId}', name: 'order_cancel')]
    public function index($stripeSessionId): Response
    {
        $order =$this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
       
        if (!$order || $order->getUsero() != $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // Envoyer un email a notre utilisateur pour lui indiquer l'echec de paiement

        return $this->render('order_cancel/index.html.twig',[
            'order' => $order
        ]);
    }
}
