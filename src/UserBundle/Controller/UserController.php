<?php

namespace App\UserBundle\Controller;

use App\UserBundle\Exception\ValidationException;
use App\UserBundle\Form\Type\RegisterFormType;
use App\UserBundle\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    private $validator;
    private $userManager;
    private $entityManager;

    public function __construct(
        ValidatorInterface $validator,
        UserManager $userManager,
        EntityManagerInterface $entityManager
    ) {
        $this->validator = $validator;
        $this->userManager = $userManager;
        $this->entityManager = $entityManager;
    }

    public function login(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_guest_book.render.guest_book');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@User/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function logout()
    {
        $this->redirectToRoute('app_user.login');
    }

    public function register(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_guest_book.render.guest_book');
        }

        $form = $this->createForm(RegisterFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $this->userManager->createUser($form->getData());
                    $this->entityManager->flush();
                    $this->addFlash('success', 'app_user.user_register_success');
                    $form = $this->createForm(RegisterFormType::class);
                } catch (ValidationException $validationException) {
                    foreach ($validationException->getData() as $error) {
                        $this->addFlash('warning', $error);
                    }
                }
            } else {
                $form = $this->createForm(RegisterFormType::class, $form->getData());
                $this->addFlash('warning', 'app_user.password_mismatch');
            }
        }
        return $this->render('@User/register.html.twig', ['registerForm' => $form->createView()]);
    }
}