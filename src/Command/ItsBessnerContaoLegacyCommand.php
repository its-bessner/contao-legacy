<?php

namespace ItsBessner\ContaoLegacy\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Connection;

class ItsBessnerContaoLegacyCommand extends Command
{

    private Connection $dbal;

    public function __construct(Connection $dbal)
    {

        $this->dbal = $dbal;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('itsbessner:contaolegacy:run')
            ->setDescription('Custom command for the ItsBessner::ContaoLegacy plugin')
            ->setHelp('Cutomize help text!');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln("Command for ItsBessner::ContaoLegacy executed");
        return 0;
    }
}