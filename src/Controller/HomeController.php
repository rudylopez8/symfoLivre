<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/apropos', name: 'app_apropos')]
    public function apropos(): Response
    {
        return $this->render('home/apropos.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/en_cour_de_dev', name: 'app_en_cour_de_dev')]
    public function enCourDeDev(): Response
    {
        $message = "Cette fonctionnalité est en cours de développement.";

        return $this->render('home/fonctionnalité_en_cour_de_dev.html.twig', [
            'message' => $message,
        ]);
    }
}
