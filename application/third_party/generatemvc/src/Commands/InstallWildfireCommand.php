<?php

namespace Generatemvc\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Generatemvc\Common\File;
use Generatemvc\Common\Tools;
use Generatemvc\Common\Commands\InstallCommand;

/**
 * Install Wildfire Command
 *
 * Installs Wildfire for CodeIgniter
 * 
 * @package Combustor
 */
class InstallWildfireCommand extends InstallCommand
{
    /**
     * @var string
     */
    protected $library = 'wildfire';

    /**
     * Executes the command.
     * 
     * @param  \Symfony\Component\Console\Input\InputInterface   $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return object|\Symfony\Component\Console\Output\OutputInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->addLibrary('wildfire');

        $template = $this->renderer->render('Libraries/Wildfire.tpl');
        $wildfire = new File(APPPATH . 'libraries/Wildfire.php');

        $wildfire->putContents($template);
        $wildfire->close();

        Tools::ignite();

        $message = 'Wildfire is now installed successfully!';

        return $output->writeln('<info>' . $message . '</info>');
    }
}
