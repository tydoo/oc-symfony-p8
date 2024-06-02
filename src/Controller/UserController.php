<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/users', name: 'user', methods: ['GET', 'POST'])]
class UserController extends AbstractController {

    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('', name: '_list', methods: ['GET'])]
    public function userList(): Response {
        return $this->render('user/user_list.html.twig', [
            'users' => $this->userRepository->findAll(),
        ]);
    }

    #[Route('/create', name: '_create')]
    public function userCreate(
        Request $request,
        Security $security
    ): Response {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->userRepository->add($user, $form->get('password')->getData());

            if ($this->getUser() === null) {
                $security->login($user, LoginFormAuthenticator::class);
                $this->addFlash('success', 'Votre compte a été créé avec succès. Vous êtes maintenant connecté. Bienvenue !');
            } else {
                $this->addFlash('success', 'L\'utilisateur a été créé avec succès.');
            }

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/user_create.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: '_edit')]
    public function userEdit(
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
