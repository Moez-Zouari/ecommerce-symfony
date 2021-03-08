<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {

        $mail = new Mail();
        $mail->send('moez.zouari.94@gmail.com','Moez Zouari','Mon premier mail','Test 2 juste njarab w bara');

        return $this->render('home/index.html.twig');
    }
}
