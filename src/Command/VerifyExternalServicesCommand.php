<?php

namespace App\Command;

use App\Service\MongoDB\MongoClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class VerifyExternalServicesCommand extends Command
{

    protected static $defaultName = 'app:verify_external_services';
    private MongoClient $mongoClient;

    /**
     * @param MongoClient $mongoClient
     */
    public function __construct(MongoClient $mongoClient)
    {
        $this->mongoClient = $mongoClient;
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $output->writeln([
            'Verifying MongoDB',
            '============',
            '',
        ]);
        try{
            $this->mongoClient->getDatabase()->selectCollection('test');
            $io->success('MongoDB normal.');
        }
        catch (\Throwable $e){

            $io->error($e->getMessage());
        }
        return Command::SUCCESS;
    }
}