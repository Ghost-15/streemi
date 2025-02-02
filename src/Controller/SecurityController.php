<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);

    }
    #[Route('/update-password', name:'app_updateO')]
    public function updatePassword(Request $request, EntityManagerInterface $entityManager,
                                   UserPasswordHasherInterface $passwordHasher, Security $security): Response
    {
        $newPassword = $request->request->get('pswd');
        $user = $security->getUser();

        if ($newPassword) {
            $user->setPassword($passwordHasher->hashPassword($user, $newPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Password updated successfully!');
        } else {
            $this->addFlash('error', 'Please provide a valid password.');
        }
        return $this->redirectToRoute('app_hub-app_profil');
    }
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
//composer require symfony/security-bundle
//php bin/console make:security:form-login