<?php

namespace Generatemvc\Commands;

use Generatemvc\Common\Commands\RemoveCommand;

/**
 * Remove Wildfire Command
 *
 * Removes Wildfire from CodeIgniter
 * 
 * @package Generatemvc
 */
class RemoveWildfireCommand extends RemoveCommand
{
    /**
     * @var string
     */
    protected $library = 'wildfire';
}
