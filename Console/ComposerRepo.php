<?php

namespace Eadesigndev\ComposerRepo\Console;

use Eadesigndev\ComposerRepo\Model\Command\Exec;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ComposerRepo
 * @package Eadesigndev\ComposerRepo\Console
 */
class ComposerRepo extends Command
{

    public $exec;

    public function __construct(
        Exec $exec,
        $name = null
    ) {
        $this->exec = $exec;
        parent::__construct($name);
    }

    public function configure()
    {
        $this->setName('eadesign:composerrepo')->setDescription('Create packages.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->exec->run();

        $output->writeln('The composer repositories are built!');
    }
}
