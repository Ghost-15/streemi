<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\AccountStatus;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private readonly EmailVerifier $emailVerifier)
    {
    }
    #[Route(path: '/register', name: 'app_register')]
    public function register(): Response
    {
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];
        return $this->render('registration/register.html.twig', [ 'roles' => $roles, ]);
    }
    #[Route(path: '/saveUser', name: 'app_save_user')]
    public function saveUser(Request $request, UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $password = $request->request->get('pswd');
        $role = $request->request->get('role');

        $app = new User();

        $app->setUsername($username);
        $app->setEmail($email);
        $app->setPassword($userPasswordHasher->hashPassword($app, $password));
        $app->setAccountStatus(accountStatus::DISPONIBLE);
        $app->setRoles((array)$role);

        try {
//            $entityManager->persist($app);
//            $entityManager->flush();

            $mail = (new Email())
                ->from('tatibatchi15@gmail.com')
                ->to('tatibatchi15@hotmail.com')
                ->subject('Test Email')
                ->text('This is a test email.');

            $mailer->send($mail);

//            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $app,
//                (new TemplatedEmail())
//                    ->from(new Address('tatibatchi15@gmail.com', 'noreply'))
//                    ->to((string) $app->getEmail())
//                    ->subject('Please Confirm your Email')
//                    ->htmlTemplate('registration/confirmation_email.html.twig')
//            );
            $this->addFlash('success', "Bienvenu sur notre plateform");
            return $this->redirectToRoute('app_register');

        } catch (TransportExceptionInterface $e){
           $this->addFlash('error', "Il y'a un probleme");
            return $this->redirectToRoute('app_register');
        }
    }
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
//composer require symfonycasts/verify-email-bundle
//php bin/console make:registration-form
//composer require symfony/mailer
//composer require symfony/google-mailer