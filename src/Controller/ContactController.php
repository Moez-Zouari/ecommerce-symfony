<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/nous-contacter', name: 'contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->addFlash('notice', 'Merci de nous avoir contacté. Notre équipe va vous répondre dans les meilleurs délais');
            
      
            $mail  = new Mail();
        //    dd($form->getData());

            $sujet = $form->get('content')->getData();
            $nom = $form->get('nom')->getData();
            $prenom = $form->get('prenom')->getData();
            $email =  $form->get('email')->getData();
            $content = "<strong>Contact Message</strong><br/><br/> <strong>Client :</strong>$prenom $nom<br/><strong>Email : </strong> $email<br/><strong>Sujet :</strong> $sujet<br/>";
            $to ='moez.zouari.94@gmail.com';
            $to_name = 'Feelcrafts';
            $mail->send($to,$to_name,'Contact Message', $content);
            
            
        }
        return $this->render('contact/index.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
