<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/register')]
class RegistrationController extends AbstractController
{
    //private EmailVerifier $emailVerifier;

   // public function __construct(EmailVerifier $emailVerifier)
    //{
        //$this->emailVerifier = $emailVerifier;
   // }
    const SITE_MANAGER = 'ROLE_SITE_MANAGER';
    const SITE_ENGINEER = 'ROLE_SITE_ENGINEER';
    const QS = 'ROLE_QS';
    const PROJECT_MANAGER = 'ROLE_PROJECT_MANAGER';
    const GM_ENGINEERING = 'ROLE_GM_ENGINEERING';
    const GM_SUPPLY_CHAIN = 'ROLE_GM_SUPPLY_CHAIN';
    const PROCUREMENT_MANAGER = 'ROLE_PROCUREMENT_MANAGER';
    const ADMIN = 'ROLE_ADMIN';
    const AUDITOR = 'ROLE_AUDITOR';

    #[Route('', name: 'app_register')]
    public function register(Request $request, LoggerInterface $logger, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {

        if ($request->isMethod("POST")) {
            try{
                $user = new User();
                $user->setFirstName(trim($request->request->get('first_name')));
                $user->setLastName(trim($request->request->get('last_name')));
                $user->setUsername(trim($request->request->get('username')));
                $user->setEmail(trim($request->request->get('email')));
                $user->setPhone(trim($request->request->get('phone')));
                $user->setSex($request->request->get('sex'));
                $user->setRoles( [$this->getRole($request)]);
                // encode the plain password
                if($request->request->get('password') === $request->request->get('password2'))
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            trim($request->request->get('password'))
                        )
                    );
                else{
                    $this->addFlash(
                        'error',
                        'Passwords Mismatch!'
                    );
                    return $this->render('registration/register.html.twig', [
                    ]);
                }
                $entityManager->persist($user);
                $entityManager->flush();
                return $this->redirectToRoute('app_login');
            }
            catch (\Throwable $e){
                $logger->error($e->getTraceAsString());
                $this->addFlash(
                    'error',
                    'Username or email already registered or password mismatch!'
                );
                return $this->render('registration/register.html.twig', ['roles'=>User::ROLES
                ]);
            }

/*
            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('elearner348563@gmail.com', 'ELearning Bot'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
*/
        }

        return $this->render('registration/register.html.twig', ['roles'=>User::ROLES
        ]);
    }


    #[Route('/verify/registration', name: 'app_verify_registration')]
    public function verifyRegistration(Request $request, UserRepository $repository): Response
    {
        $content = json_decode($request->getContent(), true);
        return new JsonResponse([
            'username' => $repository->findOneBy(['username'=> $content['username']]) || strlen(trim($content['username']) < 8)? "Username either taken or less than 8 characters!": "",
            'email' => $repository->findOneBy(['email'=> $content['email']]) || !preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/',trim($content['email'] ))? "Email either taken or not valid!": "",
            'password' => preg_match('/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).*$/',trim($content['password'])) ? "": "Password should contain at least a symbol, mix of upper and lower case letters and a number!",
            'password2' => $content['password'] === $content['password2'] ? "": "Passwords do not match!",
            /* TODO */
            'phone' => "",
            'role' => array_key_exists($content['role'], User::ROLES)? "": "Please select a role",
        ]);
    }


    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }

    private function getRole(Request $request): string{
        return User::ROLES[$request->request->get('role')];
    }
}
