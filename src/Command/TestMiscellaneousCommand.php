<?php
namespace App\Command;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\MongoDB\MongoClient;
use App\Service\Utilities\Utils;
use ReflectionClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestMiscellaneousCommand extends Command{

    protected static $defaultName = 'app:test-miscellaneous';
    private UserRepository $userRepository;


    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $one = array();
        $class = new ReflectionClass(new User());
        $output->writeln($class->getAttributes()[2]->getArguments()['message']);
        $user = $this->userRepository->find(1);
        print_r(Utils::obj2array($user));
        return Command::SUCCESS;
    }

}