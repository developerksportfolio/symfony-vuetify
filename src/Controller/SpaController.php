<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SpaController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): RedirectResponse
    {
        return $this->redirectToRoute('app_spa', ['vueRouting' => 'dashboard']);
    }

    #[Route('/app/{vueRouting}', name: 'app_spa', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => 'dashboard'])]
    public function index(): Response
    {
        return $this->render('spa/index.html.twig');
    }
}
