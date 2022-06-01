<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateAdminCommand  extends Command
{
    protected static $defaultName = 'app:create-admin';
    private UserRepository $repository;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserRepository $repository, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->repository = $repository;
        $this->userPasswordHasher = $userPasswordHasher;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setHelp($this->getCommandHelp());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $output->writeln([
            'Admin Creator',
            '============',
            '',
        ]);
        $user = new User();
        $user->setUsername($io->ask(
            'Username:', null, function ($arg) {
            if($this->repository->findOneBy(['username' => trim($arg)]))
                throw new \RuntimeException('User with chosen username already exists.');
            if(strlen(trim($arg)) < 8)
                throw new \RuntimeException('Username has less than 8 characters.');

            return $arg;
        }
        ));
        $user->setFirstName($io->ask(
            'First Name:', null, function ($arg) {
            if(!$arg)
                throw new \RuntimeException('Username has less than 8 characters.');

            return $arg;
        }
        ));
        $user->setLastName($io->ask(
            'Last Name:', null, function ($arg) {
            if(!$arg)
                throw new \RuntimeException('Username has less than 8 characters.');

            return $arg;
        }
        ));
        $user->setEmail($io->ask(
            'Email:', null, function ($arg) {
            if($this->repository->findOneBy(['email' => trim($arg)]))
                throw new \RuntimeException('User with chosen email already exists.');
            if(!preg_match('/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/',trim($arg)))
                throw new \RuntimeException('Email not valid.');

            return $arg;
        }
        ));
        $user->setPhone($io->ask(
            'Mobile Phone No:', null, function ($arg) {
            /* TODO */
            return $arg;
        }
        ));
        $user->setSex($io->choice('Select the queue to analyze', ['Male', 'Female'], 'Male') === 'Male' ? 1 : 2);

        $user->setPassword($io->askHidden('Password', function ($password) use ($user) {
            if (!preg_match('/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).*$/',trim($password))) {
                throw new \RuntimeException('Password should contain at least a symbol, mix of upper and lower case letters and a number!');
            }
            return $this->userPasswordHasher->hashPassword(
                $user,
                trim($password)
            );
        }
        ));
        $user->setRoles([User::ADMIN]);
        $user->setIsVerified(true);
        $io = new SymfonyStyle($input, $output);
        try{
            $this->repository->add($user, true);
            $io->success('User has been created.');
            return Command::SUCCESS;
        }
        catch (\Throwable $e){
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }

    private function getCommandHelp(): string
    {
        return <<<'HELP'
            The <info>%command.name%</info> command creates a new Admin user
            in your application. Follow the steps shown by the command to create an Admin for the system.
            HELP;
    }

}