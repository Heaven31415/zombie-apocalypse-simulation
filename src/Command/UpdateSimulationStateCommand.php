<?php

namespace App\Command;

use App\Service\SimulationService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'update-simulation-state',
    description: 'Updates state of the simulation',
)]
class UpdateSimulationStateCommand extends Command
{
    public function __construct(private readonly SimulationService $simulationService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->simulationService->updateState();

        $output->writeln('Simulation successfully updated!');

        return Command::SUCCESS;
    }
}
