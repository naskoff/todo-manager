<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            $this->addFlash('errors', $error->getMessage());
        }

        return $this->render('Pages/login.html.twig', ['last_username' => $authenticationUtils->getLastUsername()]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param ManagerRegistry $managerRegistry
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return Response
     */
    public function register(Request                     $request,
                             ManagerRegistry             $managerRegistry,
                             UserPasswordHasherInterface $userPasswordHasher
    ): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(UserType::class, new User(), []);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /* @var $user User */
            $user = $form->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('password')->getData()));

            $managerRegistry->getManager()->persist($user);
            $managerRegistry->getManager()->flush();

            $this->addFlash('success', 'Registration complete. Please log in.');

            return $this->redirect($this->generateUrl('app_login'));
        }

        return $this->render('Pages/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
