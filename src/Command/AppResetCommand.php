<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AppResetCommand extends Command
{
    protected static $defaultName = 'app:reset';

    protected function configure()
    {
        $this
            ->setDescription('Resetear la tabla registro');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //borrar base de datos
        $command = $this->getApplication()->find('doctrine:database:drop');
        $arguments = array(
            'command' => 'doctrine:database:drop',
            '--force'    => true,
        );
        $input = new ArrayInput($arguments);
        $command->run($input, $output);

        //crear base de datos
        $command = $this->getApplication()->find('doctrine:database:create');
        $arguments = array(
            'command' => 'doctrine:database:create',
        );
        $input = new ArrayInput($arguments);
        $command->run($input, $output);

        //crear tablas
        $command = $this->getApplication()->find('doctrine:schema:create');
        $arguments = array(
            'command' => 'doctrine:schema:create',
        );
        $input = new ArrayInput($arguments);
        $command->run($input, $output);

        $command = $this->getApplication()->find('doctrine:fixtures:load');
        $arguments = array(
            'command' => 'doctrine:fixtures:load'
        );
        $input = new ArrayInput($arguments);
        $command->run($input,$output);
    }
}
