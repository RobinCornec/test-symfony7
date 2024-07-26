<?php

namespace App\Command;

use App\Entity\Game;
use App\Entity\League;
use App\Entity\Serie;
use App\PandaScore\Api\SerieApi;
use App\Repository\GameRepository;
use App\Repository\LeagueRepository;
use App\Repository\SerieRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Generator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use function in_array;

#[AsCommand(
    name: 'hydrate:series',
    description: 'Get all series from api fram a game',
    aliases: ['h:s'],
    hidden: false
)]
class HydrateSeriesCommand extends Command
{
    const PAGE_SIZE = 100;

    /**
     * @param SerieApi $serieApi
     * @param GameRepository $gameRepository
     * @param LeagueRepository $leagueRepository
     * @param SerieRepository $serieRepository
     * @param EntityManagerInterface $entityManager
     * @param array $dbLeagues
     * @param array $dbLeaguesIdx
     * @param array $dbSeriesIdx
     */
    public function __construct(
        private readonly SerieApi $serieApi,
        private readonly GameRepository $gameRepository,
        private readonly LeagueRepository $leagueRepository,
        private readonly SerieRepository $serieRepository,
        private readonly EntityManagerInterface $entityManager,
        private array $dbLeagues = [],
        private array $dbLeaguesIdx = [],
        private array $dbSeriesIdx = [],
    ) {
        parent::__construct();

        $dbLeagues = $this->leagueRepository->findAll();
        $dbSeries = $this->serieRepository->findAll();

        foreach ($dbLeagues as $dbLeague) {
            $this->dbLeagues[$dbLeague->getPandaScoreId()] = $dbLeague;
            $this->dbLeaguesIdx[] = $dbLeague->getPandaScoreId();
        }

        foreach ($dbSeries as $dbSerie) {
            $this->dbSeriesIdx[] = $dbSerie->getPandaScoreId();
        }
    }

    protected function configure(): void
    {
        // the command help shown when running the command with the "--help" option
        $this->setHelp('This command GET PandaScore Series API and hydrate DB');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $dbGames = $this->gameRepository->findAll();

            if (empty($dbGames)) {
                return Command::SUCCESS;
            }

            foreach ($dbGames as $dbGame) {
                $pandaScoreId = $dbGame->getPandaScoreId();

                $series = $this->serieApi->getAllByGame($pandaScoreId);
                $this->addSeriesAndLeagues($series, $dbGame);
            }

            $this->entityManager->flush();
            return Command::SUCCESS;
        } catch (Exception $exception) {
            dd($exception);
            return Command::FAILURE;
        }
    }

    /**
     * @param Generator $psSeries
     * @param Game $game
     * @return void
     * @throws Exception
     */
    private function addSeriesAndLeagues(Generator $psSeries, Game $game): void
    {
        foreach ($psSeries as $psSerie) {
            if (!in_array($psSerie->league->id, $this->dbLeaguesIdx)) {
                $newLeague = new League(
                    $psSerie->league->id,
                    $psSerie->league->name,
                    $psSerie->league->slug,
                    true,
                    $game,
                );

                $this->dbLeagues[$psSerie->league->id] = $newLeague;
                $this->dbLeaguesIdx[] = $psSerie->league->id;
                $this->entityManager->persist($newLeague);
            }

            if (!in_array($psSerie->id, $this->dbSeriesIdx)) {
                $newSerie = new Serie(
                    $psSerie->id,
                    $psSerie->fullName,
                    $psSerie->slug,
                    true,
                    $psSerie->winnerId,
                    $psSerie->winnerType,
                    $psSerie->beginAt,
                    $psSerie->endAt,
                    $newLeague ?? $this->dbLeagues[$psSerie->league->id],
                    $game
                );

                $this->dbSeriesIdx[] = $psSerie->id;
                $this->entityManager->persist($newSerie);
            }
        }
    }
}
