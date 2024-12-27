<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Media;
use App\Entity\Subscription;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hub', name: 'app_hub-')]
class HubController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('hub/index.html.twig');
    }
    #[Route('/subscription', name: 'app_subscription')]
    public function subscription(EntityManagerInterface $entityManager): Response
    {
        $subscriptions = $entityManager->getRepository(Subscription::class)->findAll();
        return $this->render('hub/subscriptions.html.twig', [
            'subscriptions' => $subscriptions,
        ]);
    }
    #[Route('/discover', name: 'app_discover')]
    public function discover(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(Categorie::class)->findAll();
        return $this->render('hub/discover.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/categorie/{value}', name: 'app_categorie')]
    public function categorie(EntityManagerInterface $entityManager, MediaRepository $mediaRepository, string $value): Response
    {
        $categorie = $entityManager->getRepository(Categorie::class)->findOneBy(['name' => $value]);
        $medias = $mediaRepository->findAllWhere(['value' => $value]);
        return $this->render('hub/category.html.twig', [
            'categorie' => $categorie,
            'medias' => $medias,
        ]);
    }
    #[Route('/list', name: 'app_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $medias = $entityManager->getRepository(Media::class)->findAll();
        return $this->render('hub/lists.html.twig', [
            'medias' => $medias,
        ]);
    }
    #[Route('/detail/{value}', name: 'app_detail')]
    public function detail(EntityManagerInterface $entityManager, string $value): Response
    {
        $media = $entityManager->getRepository(Media::class)->findOneBy(['title' => $value]);
        return $this->render('hub/detail.html.twig', [
            'media' => $media,
            'jsonStaff' => $media->getStaff(),
            'jsonCast' => $media->getCast(),
        ]);
    }
    #[Route('/detail-serie', name: 'app_detail_serie')]
    public function detail_serie(): Response
    {
        return $this->render('hub/detail-serie.html.twig');
    }
}
