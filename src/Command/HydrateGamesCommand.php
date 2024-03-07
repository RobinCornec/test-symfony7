<?php

namespace App\Command;

use App\Entity\Game;
use App\PandaScore\Api\GameApi;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
    name: 'hydrate:games',
    description: 'Get all games from api',
    aliases: ['h:g'],
    hidden: false
)]
class HydrateGamesCommand extends Command
{
    public function __construct(
        private readonly GameApi $gameApi,
        private readonly GameRepository $gameRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        // the command help shown when running the command with the "--help" option
        $this->setHelp('This command GET PandaScore Games API and hydrate DB');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $psGames = $this->gameApi->getAll();

            $dbGames = $this->gameRepository->findAll();
            $dbPsIdx = [];

            foreach ($dbGames as $dbGame) {
                $dbPsIdx[] = $dbGame->getPandaScoreId();
            }

            foreach ($psGames as $psGame) {
                if (!in_array($psGame->id, $dbPsIdx)) {
                    $newGame = new Game(
                        $psGame->id,
                        $psGame->name,
                        $psGame->slug,
                    );

                    $this->entityManager->persist($newGame);
                }
            }

            $this->entityManager->flush();

            return Command::SUCCESS;
        } catch (Exception $exception) {
            echo $exception->getMessage();

            return Command::FAILURE;
        }
    }
}
