<?php

namespace Eadesigndev\ComposerRepo\Console;

use Magento\Framework\App\Action\Context;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ComposerRepo
 * @package Eadesigndev\ComposerRepo\Console
 */
class ComposerRepo extends Command
{

    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('eadesign:composerrepo')->setDescription('Prints hello world.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello World!');
    }
}