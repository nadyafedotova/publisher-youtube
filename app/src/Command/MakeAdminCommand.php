<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\RoleService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:makeAdmin', description: 'Promotes user to be admin')]
class MakeAdminCommand extends Command
{
    public function __construct(
        readonly private RoleService $roleService,
    ) {
        parent::__construct();
    }

    final protected function configure(): void
    {
        $this->addArgument('user-id', InputArgument::REQUIRED, 'User ID');
    }

    final protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = (int) $input->getArgument('user-id');
        $this->roleService->grantAdmin($userId);

        return Command::SUCCESS;
    }
}
