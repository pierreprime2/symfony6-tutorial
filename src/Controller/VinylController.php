<?php

namespace App\Controller;

use App\Service\MixRepository;
use Psr\Cache\CacheItemInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Twig\Environment;
use function Symfony\Component\String\u;

class VinylController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function homepage(Environment $twig): Response
    {
        $tracks = [
            ['song' => 'Gangsta\'s Paradise', 'artist' => 'Coolio'],
            ['song' => 'Waterfalls', 'artist' => 'TLC'],
            ['song' => 'Creep', 'artist' => 'Radiohead'],
            ['song' => 'Kiss from a Rose', 'artist' => 'Seal'],
            ['song' => 'On Bended Knee', 'artist' => 'Boyz II Men'],
            ['song' => 'Fantasy', 'artist' => 'Mariah Carey'],
        ];
//        dump($tracks);

//        return $this->render('vinyl/homepage.html.twig', [
//            'title' => 'PB & Jams',
//            'tracks' => $tracks,
//        ]);
        $html = $twig->render('vinyl/homepage.html.twig', [
            'title' => 'PB & Jams',
            'tracks' => $tracks,
        ]);
        return new Response($html);
//        dd($html);
    }

    #[Route('/browse/{slug}', name: 'app_browse')]
    public function browse(HttpClientInterface $httpClient, CacheInterface $cache, MixRepository $mixRepository, string $slug = null): Response
    {
        $genre = $slug ? u(str_replace('-', '', $slug))->title(true) : null;

//        dump($cache);
//        $mixes = $cache->get('mixes_data', function (CacheItemInterface $cacheItem) use ($httpClient) {
//            // expires after 5 seconds
//            $cacheItem->expiresAfter(5);
//            $response = $httpClient->request('GET', 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json');
//            return $response->toArray();
//        });

        $mixes = $mixRepository->findAll();

        return $this->render('vinyl/browse.html.twig', [
            'genre' => $genre,
            'mixes' => $mixes,
        ]);
    }
}