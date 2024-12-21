<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HubController extends AbstractController
{
    #[Route('/hub', name: 'app_hub')]
    public function index(): Response
    {
        return $this->render('hub/index.html.twig');
    }
    #[Route('/subscription', name: 'app_subscription')]
    public function subscription(): Response
    {
        return $this->render('hub/subscriptions.html.twig');
    }
    #[Route('/discover', name: 'app_discover')]
    public function discover(): Response
    {
        return $this->render('hub/discover.html.twig');
    }
    #[Route('/categorie', name: 'app_cat')]
    public function categorie(): Response
    {
        return $this->render('hub/category.html.twig');
    }
    #[Route('/list', name: 'app_list')]
    public function list(): Response
    {
        return $this->render('hub/lists.html.twig');
    }
    #[Route('/detail', name: 'app_detail')]
    public function detail(): Response
    {
        return $this->render('hub/detail.html.twig');
    }
    #[Route('/detail_serie', name: 'app_detail_serie')]
    public function detail_serie(): Response
    {
        return $this->render('hub/detail_serie.html.twig');
    }
}
