<?php

namespace Generatemvc\Generator;

/**
 * Generator Interface
 *
 * An interface for generators.
 * 
 * @package Generatemvc
 */
interface GeneratorInterface
{
    /**
     * Generates set of code based on data.
     * 
     * @return array
     */
    public function generate();
}
