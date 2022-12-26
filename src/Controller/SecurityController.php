<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/admin/login', name: 'admin.login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/admin/users', name: 'admin.users')]
    public function users(): Response
    {
        return $this->render('users.html.twig', [
            'users' => $this->userService->getUsersWithoutPrivileges(),
        ]);
    }

    #[Route('/admin/register/submit', name: 'admin.register.submit')]
    public function register(Request $request, Security $security): Response
    {
        $credentials = $request->request->all();
        $user = $this->userService->registerUser($credentials);
        if ($user) {
//            $security->login($user);
            return $this->render('login.html.twig', [
                'message' => 'Your registration is being reviewed',
            ]);
        } else {
            return $this->render('register.html.twig', [
                'error' => 'Something went wrong. Try again',
            ]);
        }
    }

    #[Route('/admin/register', name: 'admin.register')]
    public function registerForm(): Response
    {
        return $this->render('register.html.twig');
    }

    #[Route('/admin/logout', name: 'admin.logout')]
    public function logout(Security $security): Response
    {
        $security->logout(false);
        return $this->redirect('/admin/login');
    }

    #[Route('/admin/privilege/grant/{id}', name: 'admin.grant')]
    public function grantPrivileges(int $id): Response
    {
        $this->userService->grantPrivileges($id);
        return $this->redirect('/admin/users');
    }

    #[Route('admin/privilege/refuse/{id}', name: 'admin.refuse')]
    public function refusePrivileges(int $id): Response
    {
        $this->userService->refusePrivileges($id);
        return $this->redirect('/admin/users');
    }
}