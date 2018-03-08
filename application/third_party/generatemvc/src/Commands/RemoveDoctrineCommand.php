<?php

namespace Generatemvc\Commands;

use Generatemvc\Common\Commands\RemoveCommand;

/**
 * Remove Doctrine Command
 *
 * Removes Doctrine from CodeIgniter
 * 
 * @package Generatemvc
 */
class RemoveDoctrineCommand extends RemoveCommand
{
    /**
     * @var string
     */
    protected $library = 'doctrine';
}
