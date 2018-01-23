<?php

namespace App\Controller;

use App\Entity\User;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controller used to manage the application security.
 */
class SecurityController extends AbstractController
{

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, Validator $validator)
    {
        $this->em = $em;
        $this->passwordEncoder = $encoder;
        $this->validator = $validator;
    }
    /**
     * @Route("/login", name="security_login")
     * @param Request $request
     * @param AuthenticationUtils $authUtils
     * @return Response
     */
    public function login(Request $request, AuthenticationUtils $authUtils): Response
    {
        return $this->render('security/login.html.twig', [
            // last username entered by the user (if any)
            'last_username' => $authUtils->getLastUsername(),
            // last authentication error (if any)
            'error' => $authUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/signup", name="security_signup")
     * @param Request $request
     * @return Response
     */
    public function signup(Request $request): Response
    {

        $form = $this->createFormBuilder(['caption' => ''])
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('full-name', TextType::class)
            ->add('password', PasswordType::class)
            ->add('save', SubmitType::class, ['label' => 'Sign Up'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $username = $form['username']->getData();
            $email = $form['email']->getData();
            $plainPassword = $form['password']->getData();
            $fullName = $form['full-name']->getData();

            // make sure to validate the user data is correct
            $this->validateUserData($username, $plainPassword, $email, $fullName);

            $user = new User();
            $user->setFullName($fullName);
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setRoles(['ROLE_USER']);

            $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
            $user->setPassword($encodedPassword);

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('security_login');

        }

        return $this->render('security/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in app/config/security.yml
     *
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

    private function validateUserData($username, $plainPassword, $email, $fullName)
    {
        $users = $this->getDoctrine()->getRepository(User::class);
        $existingUser = $users->findOneBy(['username' => $username]);

        if (null !== $existingUser) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" username.', $username));
        }

        $this->validator->validatePassword($plainPassword);
        $this->validator->validateEmail($email);
        $this->validator->validateFullName($fullName);

        $existingEmail = $users->findOneBy(['email' => $email]);

        if (null !== $existingEmail) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
        }
    }
}
