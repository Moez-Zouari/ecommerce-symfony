<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/inscription', name: 'register')]
    
    /* Injection de depandance request, dire a symfony je veux que tu rentres
    dans ma fonction public index en embarquon avec toi l'objet request  */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User(); //Instancier mon user
        $form = $this->createForm(RegisterType::class, $user); //Instacier mon formulaire

        /*Des que ce formulaire est soumis, je veux que tu traite linformation
       et regarde si tout va bien (formulaire valide ?) et l'enregistre dans la bd
       */
        $form->handleRequest($request); //


        if ($form->isSubmitted() && $form->isValid()) {

            //Injecter dans l'objet user toutes les donnés recupérés depuis le formulare
            $user = $form->getData();

            //Pour encoder le mot de passe
            $password = $encoder->encodePassword($user, $user->getPassword());

            // Pour sauvegarder le mot de passe encoder dans la bd 
            $user->setPassword($password);

            //Percister c'est a dire fije la data parceque j'ai besoin de l'enregistrer
            $this->entityManager->persist($user);

            /*  flush tu executes la persistance, tu prends la data l'objet que tu l'a fijer
            et tu l'enregistre dans la BD */
            $this->entityManager->flush();
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
