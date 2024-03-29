<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/app', name: 'app')]
    public function app(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findBy([
            'active' => true
        ]);


        return $this->render('app.html.twig', [
            'games' => $games,
        ]);
    }

//    #[Route('/home', name: 'home')]
//    public function index(): Response
//    {
//        $matches = [
//            [
//                'id' => 1,
//                'opponent1' => 'Team 1',
//                'opponent2' => 'Team 2',
//                'result' => '2-0',
//            ],
//
//                'id' => 2,
//                'opponent1' => 'Team 3',
//                'opponent2' => 'Team 4',
//                'result' => '1-2',
//            ],
//        ];
//
//        return $this->render('app.html.twig', [
//            'matches' => $matches,
//        ]);
//    }

//    #[Route('/add', name: 'app_add_match')]
//    public function addMatch(HubInterface $hub): Response
//    {
//        $match = [
//            'id' => 3,
//            'opponent1' => 'Team 5',
//            'opponent2' => 'Team 6',
//            'result' => '2-0',
//        ];
//
//        $hub->publish(new Update(
//            'list_matches',
//            $this->renderView('new-match.html.twig', [
//                'match' => $match,
//            ])
//        ));
//
//        return new Response('Success!');
//    }
}
