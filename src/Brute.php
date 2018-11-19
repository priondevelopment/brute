<?php

namespace Brute;

/**
 * This class is the main entry point of laratrust. Usually this the interaction
 * with this class will be done through the Laratrust Facade
 *
 * @license MIT
 * @package Laratrust
 */

class Brute
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    protected $methods = [
        'attempt' => Attempt::class,
        'block' => Block::class,
    ];

    /**
     * Create a new confide instance.
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }


    /**
     * Load Brute Classes
     *
     *
     */
    public function get($method)
    {
        $method = strtolower($method);
        return new $this->methods[$method];
    }
}