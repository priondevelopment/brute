<?php

namespace Brute;

/**
 * This file is part of Prion Development Brute,
 * monitoring and managing brute force attempts.
 *
 * @license MIT
 * @package Brute
 */

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class BruteServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        'Config' => 'command.prionbrute.config',
    ];


    /**
     * The middlewares to be registered.
     *
     * @var array
     */
    protected $middlewares = [
    ];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Register published configuration.
        $app_path = app()->basePath('config/prionbrute.php');
        $this->publishes([
            __DIR__ . '/config/prionbrute.php' => $app_path,
        ], 'prionbrute');
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBrute();

        $this->registerCommands();

        $this->mergeConfig();
    }


    /**
     * Register PrionUsers Package in Laravel/Lumen
     *
     */
    protected function registerBrute()
    {
        $this->app->bind('brute', function ($app) {
            return new Brute($app);
        });

        $this->app->alias('brute', 'Brute\Brute');

    }


    /**
     * Register the Available Commands
     *
     */
    protected function registerCommands ()
    {
        foreach (array_keys($this->commands) as $command) {
            $method = "register{$command}Command";

            call_user_func_array([$this, $method], []);
        }

        $this->commands(array_values($this->commands));
    }


    /**
     * Merge Configuration Settings at run time. If Brute has not run
     * the configuration setup command, the default setings are merged in
     *
     */
    protected function mergeConfig ()
    {
        $this->app->configure('prionbrute');
        $this->mergeConfigFrom(
            __DIR__.'/config/prionbrute.php',
            'prionbrute'
        );
    }


    /**
     * Register the Config Command
     *
     */
    protected function registerConfigCommand()
    {
        $command = $this->commands['Config'];
        $this->app->singleton($command, function () {
            return new \Brute\Commands\ConfigCommand;
        });
    }
}