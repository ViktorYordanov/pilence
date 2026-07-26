<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Symfony\Component\Validator\Validation;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create (or update) an admin user directly in the database',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::OPTIONAL, 'The admin email address')
            ->addArgument('password', InputArgument::OPTIONAL, 'The admin password')
            ->addOption('update', null, InputOption::VALUE_NONE, 'Update the password if the user already exists');
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $io = new SymfonyStyle($input, $output);

        if (null === $input->getArgument('email')) {
            $input->setArgument('email', $io->ask('Email'));
        }

        if (null === $input->getArgument('password')) {
            $question = (new Question('Password'))
                ->setHidden(true)
                ->setHiddenFallback(false);
            $input->setArgument('password', $io->askQuestion($question));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = trim((string) $input->getArgument('email'));
        $password = (string) $input->getArgument('password');
        $allowUpdate = (bool) $input->getOption('update');

        // --- validate input --------------------------------------------------
        $validator = Validation::createValidator();
        if ('' === $email || \count($validator->validate($email, new EmailConstraint())) > 0) {
            $io->error('Please provide a valid email address.');

            return Command::INVALID;
        }

        if (\strlen($password) < 8) {
            $io->error('Password must be at least 8 characters long.');

            return Command::INVALID;
        }

        // --- create or update ------------------------------------------------
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (null !== $user && !$allowUpdate) {
            $io->error(sprintf('A user with email "%s" already exists. Re-run with --update to reset its password.', $email));

            return Command::FAILURE;
        }

        $isNew = null === $user;
        if ($isNew) {
            $user = new User();
            $user->setEmail($email);
        }

        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf(
            '%s admin user "%s".',
            $isNew ? 'Created' : 'Updated',
            $email
        ));

        return Command::SUCCESS;
    }
}
