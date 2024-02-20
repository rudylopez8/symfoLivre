<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/tools', name: 'admin_tools')]
    public function tools(): Response
    {
        return $this->render('admin/tools.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/compte', name: 'admin_compte')]
    public function compte(): redirectResponse
    {
        return $this->redirectToRoute('app_user_show',[
            'user' => $user,
        ] );
    }
}
