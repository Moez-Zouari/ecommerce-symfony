<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'account_password')]
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $notification = null;

        $user = $this->getUser(); //Appeler l'objet utilisateur connecter et l'injecter dans user
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Recupérer l'ancien mdp (mdp a changer) de l'utilsateur en question 
            $old_pwd = $form->get('old_password')->getData();
            //dd(old_pwd) Pour tester

            // On va  verifier le mdp a changer avec celle dans la bd  de l'utilisateur en question
            if ($encoder->isPasswordValid($user, $old_pwd)) {
                //die('ca marche'); Pour tester

                // Si toute est bon je dois recupérer le nouveau mdp saisi dans le formulaire
                $new_pwd = $form->get('new_password')->getData();

                // Encoder le nouveau mdp
                $password = $encoder->encodePassword($user, $new_pwd);

                //Setter le nouveau psw dans l'objet User
                $user->setPassword($password);


                /*  flush tu executes la persistance, tu prends la data l'objet que tu l'a fijer
                et tu l'enregistres dans la BD */
                $this->entityManager->flush();

                $notification = "Votre mot de passe a bien été mis à jour";
            } else {
                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(), // Il faut un create view pouir le passer à mon TWIG
            'notification' => $notification
        ]);
    }
}
