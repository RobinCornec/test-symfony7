<?php

namespace App\Command;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'hydrate:game',
    description: 'Get all games from api',
    aliases: ['h:g'],
    hidden: false
)]
class HydrateGameCommand extends Command
{
    public function __construct(
        private readonly HttpClientInterface $pandascoreClient,
        private readonly GameRepository $gameRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        // the command help shown when running the command with the "--help" option
        $this->setHelp('This command GET PandaScore API and hydrate DB');
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
            $response = $this->pandascoreClient->request(
                'GET',
                '/videogames'
            );

            $dbGames = $this->gameRepository->findAll();
            $dbPsIdx = [];

            foreach ($dbGames as $dbGame) {
                $dbPsIdx[] = $dbGame->getPandaScoreId();
            }

            foreach (\json_decode($response->getContent(), true) as $PsGame) {
                if (!\in_array($PsGame['id'], $dbPsIdx)) {
                    $newGame = new Game(
                        $PsGame['id'],
                        $PsGame['name'],
                        $PsGame['slug'],
                    );

                    $this->entityManager->persist($newGame);
                }
            }

            $this->entityManager->flush();

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            echo $exception->getMessage();

            return Command::FAILURE;
        }
    }
}