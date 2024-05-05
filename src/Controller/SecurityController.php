<?php

namespace App\Controller;

use LogicException;
use App\Form\LoginType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController {
    #[Route(path: '/login', name: 'login', methods: ['GET', 'POST'])]
    public function login(
        Request $request,
        AuthenticationUtils $authenticationUtils
    ): Response {
        if ($this->getUser()) {
            return new RedirectResponse($this->generateUrl('homepage'));
        }

        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);
        $form->get('username')->setData($authenticationUtils->getLastUsername());

        return $this->render('security/login.html.twig', [
            'loginForm' => $form,
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
