<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $utils): Response
    {
        if ($this->getUser() === null) {
            $error = $utils->getLastAuthenticationError();
            $email = $utils->getLastUsername();
    
            return $this->render('account/login.html.twig', [
                'hasError' => $error !== null,
                'email' => $email
            ]);

        }else {
            return $this->redirectToRoute('dashboard');
        }
    }
    #[Route('/logout', name: 'logout')]
    public function logout()
    {
        
    }
}
