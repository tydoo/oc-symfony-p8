<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController {

    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    #[Route('/users/create', name: 'user_create', methods: ['GET', 'POST'])]
    public function user_create(
        Request $request,
        Security $security
    ): Response {
        if ($this->getUser()) {
            return new RedirectResponse($this->generateUrl('homepage'));
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->userRepository->add($user, $form->get('plainPassword')->getData());

            $security->login($user, LoginFormAuthenticator::class);

            $this->addFlash('success', 'Votre compte a été créé avec succès. Vous êtes maintenant connecté. Bienvenue !');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/user_create.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/users/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function user_edit(
        User $user,
        Request $request
    ): Response {
        if ($this->getUser() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository->update($user, $form->get('password')->getData());

            $this->addFlash('success', 'L\'utilisateur a été modifié avec succès.');

            return $this->redirect($request->headers->get('referer') ?? $this->generateUrl('homepage'));
        }

        return $this->render('user/user_edit.html.twig', [
            'user' => $user,
            'userForm' => $form,
        ]);
    }
}
