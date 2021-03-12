<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mot-de-passe-oublie', name: 'reset_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($request->get('email')) {
            // Chercher le mail saisi existe ou non 
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            // I : Enregistrer en base la demande de reset_password avec user, Token, CreatedAt
            if ($user) {
                $reset_password = new ResetPassword();
                $reset_password->setMyUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTime());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                // 2 : Envoyer un email à l'utilisateur avec un lien lui permettant de mettre à jour son mdp
                $url = $this->generateUrl('update_password', [
                    'token' => $reset_password->getToken()
                ]);

                $mail  = new Mail();
                $content = "Bonjour " . $user->getFirstname() . "<br/>Vous avez demandé à réinitialiser votre mot de passe sur le site Feelcrafts.<br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='.$url'>mettre à jour votre mot de passe</a>";
                $mail->send($user->getEmail(), $user->getFirstname() . ' ' . $user->getLastname(), 'Réinitialiser votre mot de passe sur Feelcrafts', $content);
                $this->addFlash('notice', 'Vous allez recevoir dans quelques secondes un mail avec la procédure pour réinitialiser votre mot de passe.');
            } else {
                $this->addFlash('notice', 'Cette adresse e mail est inconnu.');
            }
        }


        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'update_password')]
    public function update(Request $request, $token, UserPasswordEncoderInterface $encoder): Response
    {
        // Chercher le token envoyé dans lurl et le recupérer
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        // Si il n'apas trouver , alors redirection vers resert password
        if (!$reset_password) {
            return $this->redirectToRoute('reset_password');
        }

        // Verifier si le createdAt == now -3h
        $now = new \DateTime();

        if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('notice', "Votre demande de mot de passe a expiré. Merci de la renouveller");
            return $this->redirectToRoute('reset_password');
        }

        // Rendre une vue avec mot de passe et confirmez votre mot de passe
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd = $form->get('new_password')->getData();


            // Encoder le mdp
            $password = $encoder->encodePassword($reset_password->getMyUser(), $new_pwd);
            $reset_password->getMyUser()->setPassword($password);

            // Flus en bd
            $this->entityManager->flush();

            // Redirection de l'utlisateur vers la page de connexion
            $this->addFlash('notice', 'Votre mot de passe a bien été mise à jour');

            return $this->redirectToRoute('app_login');
        }


        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
