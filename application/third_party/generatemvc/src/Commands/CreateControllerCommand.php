<?php

namespace Generatemvc\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Generatemvc\Common\File;
use Generatemvc\Common\Tools;
use Generatemvc\Validator\ControllerValidator;
use Generatemvc\Generator\ControllerGenerator;

/**
 * Create Controller Command
 *
 * Generates a Wildfire or Doctrine-based controller for CodeIgniter.
 * 
 * @package Combustor
 * @author  Generatemvc Royce Gutib <rougingutib@gmail.com>
 */
class CreateControllerCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $command = 'controller';

    /**
     * Executes the command.
     * 
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return object|\Symfony\Component\Console\Output\OutputInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileName = ucfirst(plural($input->getArgument('name')));

        if ($input->getOption('keep')) {
            $fileName = ucfirst($input->getArgument('name'));
        }

        $path = APPPATH . 'controllers' . DIRECTORY_SEPARATOR . $fileName . '.php';

        $info = [
            'name' => $fileName,
            'type' => 'controller',
            'path' => $path
        ];

        $validator = new ControllerValidator($input->getOption('camel'), $info);

        if ($validator->fails()) {
            $message = $validator->getMessage();

            return $output->writeln('<error>' . $message . '</error>');
        }

        $data = [
            'file' => $info,
            'isCamel' => $input->getOption('camel'),
            'name' => $input->getArgument('name'),
            'title' => strtolower($fileName),
            'type' => $validator->getLibrary()
        ];

        $generator = new ControllerGenerator($this->describe, $data);

        $result = $generator->generate();
        $controller = $this->renderer->render('Controller.tpl', $result);
        $message = 'The controller "' . $fileName . '" has been created successfully!';

        $file = new File($path);

        $file->putContents($controller);
        $file->close();

        return $output->writeln('<info>' . $message . '</info>');
    }
}
