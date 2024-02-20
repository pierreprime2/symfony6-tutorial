<?php

namespace App\Controller;

use App\Repository\VinylMixRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;
use function Symfony\Component\String\u;

//use App\Service\MixRepository;

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
    public function browse(VinylMixRepository $mixRepository, string $slug = null): Response
    {
        $genre = $slug ? u(str_replace('-', '', $slug))->title(true) : null;

//        dump($cache);
//        $mixes = $cache->get('mixes_data', function (CacheItemInterface $cacheItem) use ($httpClient) {
//            // expires after 5 seconds
//            $cacheItem->expiresAfter(5);
//            $response = $httpClient->request('GET', 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json');
//            return $response->toArray();
//        });

        $mixes = $mixRepository->findAllOrderByVotes($slug);
//        dd($mixes);

        return $this->render('vinyl/browse.html.twig', [
            'genre' => $genre,
            'mixes' => $mixes,
        ]);
    }
}