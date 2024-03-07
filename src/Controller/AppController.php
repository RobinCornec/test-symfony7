<?php

namespace App\Controller;

use App\Repository\GameRepository;
use App\Repository\SerieRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\UuidV7;

#[Route('/app')]
class AppController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/', name: 'app')]
    public function app(GameRepository $gameRepository, SerieRepository $serieRepository): Response
    {
        $games = $gameRepository->findBy([
            'active' => true
        ]);

        $ongoingSeries = $serieRepository->findActiveByGame();
        $presentedSeries = [];

        if (null !== $ongoingSeries) {
            foreach ($ongoingSeries as $ongoingSerie) {
                $presentedSeries[$ongoingSerie['game_id']->toRfc4122()] = $ongoingSerie['ongoing_series'];
            }
        }

        return $this->render('app.html.twig', [
            'games' => $games,
            'ongoing_serie' => $presentedSeries,
        ]);
    }

    /**
     * @throws Exception
     */
    #[Route('{id}/series', name: 'series')]
    public function tournaments(UuidV7 $id, SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findActiveByGameId($id);

        return $this->render('turbo-frame/series.html.twig', [
            'series' => $series,
        ]);
    }
}
