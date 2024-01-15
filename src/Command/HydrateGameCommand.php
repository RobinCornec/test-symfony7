<?php

namespace App\Command;

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
        $response = $this->pandascoreClient->request(
            'GET',
            '/videogames'
        );

        foreach (\json_decode($response->getContent()) as $game) {
            dump($game);
        }

        //Check if exists in bdd else create new entity Game :)

        return Command::SUCCESS;
    }
}