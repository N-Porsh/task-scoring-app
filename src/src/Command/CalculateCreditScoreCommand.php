<?php

namespace App\Command;

use App\Repository\ClientRepository;
use App\Service\ScoringEngine;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 *
 *
 *
 * Class CalculateCreditScoreCommand
 * @package App\Command
 */
class CalculateCreditScoreCommand extends Command
{
    protected static $defaultName = 'calculate-credit-score';

    private $logger;
    private $scoringEngine;
    private $clientRepository;

    public function __construct(LoggerInterface $logger, ScoringEngine $scoringEngine, ClientRepository $clientRepository)
    {
        $this->logger = $logger;
        $this->clientRepository = $clientRepository;
        $this->scoringEngine = $scoringEngine;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Calculates credit score for all clients or for given clientId')
            ->addArgument('client_id', InputArgument::OPTIONAL, 'Should be {INT} value');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $clientId = $input->getArgument('client_id');

        $data = [];
        if (!$clientId) {
            // calculate for all clients
            $clients = $this->clientRepository->findAll();
            $io->note('Recalculate credit score for all clients...');
            foreach ($clients as $client) {
                $this->logger->info(sprintf('Recalculate credit score for clientID: %s', $client->getId()));
                $this->scoringEngine->calculate($client);
            }

            $data = $this->clientRepository->getAllClientsArray();
        } else {
            $client = $this->clientRepository->find($clientId);
            if (!$client) {
                $io->warning(sprintf("Client not found. ID: %d", $clientId));
                return 0;
            }
            $this->logger->info(sprintf('Recalculate credit score for clientID: %s', $client->getId()));
            $this->scoringEngine->calculate($client);
            $data = $this->clientRepository->getClientAsArray($clientId);
        }

        $table = new Table($output);
        $table
            ->setHeaders(['ClientID', 'Email', 'Score'])
            ->setRows($data);
        $table->render();

        $io->success("Credit score recalculated");

        return 0;
    }
}
