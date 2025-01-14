<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Media;
use App\Entity\Playlist;
use App\Entity\PlaylistSubscription;
use App\Entity\Subscription;
use App\Entity\User;
use App\Repository\MediaRepository;
use App\Repository\PlaylistMediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hub', name: 'app_hub-')]
class HubController extends AbstractController
{
    #[Route('', name: 'app_index')]
    public function index(MediaRepository $mediaRepository): Response
    {
        $popularMedias = $mediaRepository->findPopular();
        return $this->render('hub/index.html.twig',[
            'popularMedias' => $popularMedias,
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
    #[Route('/list', name: 'app_list')]
    public function list(Request $request, EntityManagerInterface $entityManager, PlaylistMediaRepository $playlistMediaRepository, MediaRepository $mediaRepository): Response
    {
        $playlists = $entityManager->getRepository(Playlist::class)->findAll();
        $playlistSubscriptions = $entityManager->getRepository(PlaylistSubscription::class)->findAll();
        $selectedPlaylistId = $request->query->get('selectedPlaylist');
        $medias = [];
        if ($selectedPlaylistId) {
            $playlistMediaArray = $playlistMediaRepository->findAllMediaWhere(['value'=>$selectedPlaylistId]);
            if ($playlistMediaArray) {
                foreach ($playlistMediaArray as $playlistMedia) {
                    $m = $playlistMedia->getMedia();
                    $medias[] = [
                        'id' => $m->getId(),
                        'mediaType' => $m->getMediaType(),
                        'title' => $m->getTitle(),
                        'shortDescription' => $m->getShortDescription(),
                        'longDescription' => $m->getLongDescription(),
                        'releaseDate' => $m->getReleaseDate()->format('Y-m-d'),
                        'coverImage' => $m->getCoverImage()
                    ];
                }
            }
        }

        return $this->render('hub/lists.html.twig', [
            'playlists' => $playlists,
            'playlistSubscriptions' => $playlistSubscriptions,
            'selectedPlaylistId' => $selectedPlaylistId,
            'medias' => $medias,
        ]);
    }
    #[Route('/profil', name: 'app_profil')]
    public function profil(EntityManagerInterface $entityManager): Response
    {
        return $this->render('hub/profil.html.twig');
    }
    #[Route('/subscription', name: 'app_subscription')]
    public function subscription(EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $security->getUser()->getUserIdentifier()]);
        $subscriptions = $entityManager->getRepository(Subscription::class)->findAll();
        return $this->render('hub/subscriptions.html.twig', [
            'sub' => $user->getSubscription()->getName(),
            'subscriptions' => $subscriptions,
        ]);
    }
    #[Route('/subscription/{value}', name: 'app_subscription_user')]
    public function subscriptionValue(EntityManagerInterface $entityManager, int $value, Security $security): Response
    {
        $subscription = $entityManager->getRepository(Subscription::class)->find($value);
        $user = $security->getUser();
        if($value){
            $user->setSubscription($subscription);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "C'est bon!");
        } else {
            $this->addFlash('error', 'Erreur...');
        }
        return $this->render('hub/subscriptions.html.twig');
    }
    #[Route('/categorie/{value}', name: 'app_categorie')]
    public function categorie(EntityManagerInterface $entityManager, MediaRepository $mediaRepository, string $value): Response
    {
        $categorie = $entityManager->getRepository(Categorie::class)->findOneBy(['name' => $value]);
        $medias = $mediaRepository->findAllWhereMediaType(['value' => $value]);
        return $this->render('hub/category.html.twig', [
            'categorie' => $categorie,
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
