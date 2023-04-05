<?php

namespace Smartech\Subscriptions;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;
use Smartech\Subscriptions\Commands\SubscriptionsMigrateCommand;

/**
 * Class ServiceProvider
 * @package Smartech\Subscriptions
 */
class SubscriptionServiceProvider extends ServiceProvider
{

    private $basePath;

    /**
     * ServiceProvider constructor.
     * @param Application $app
     */
    function __construct($app)
    {
        $this->app = $app;
        $this->basePath = dirname(dirname(__FILE__));
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Config Publishing

        $this->publishes([
            $this->basePath . '/config/' => $this->app->basePath() . '/config',
        ], "subscriptions.config");

        // Load translations files

        $this->loadTranslationsFrom($this->basePath . "/resources/lang", "subscriptions");

        // Load migration files

        $this->loadMigrationsFrom($this->basePath . "/database/migrations");
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            $this->basePath . '/config/subscriptions.php',
            'subscriptions'
        );

        // Registering models into IOC

        foreach ($this->app['config']["subscriptions"]["models"] as $service => $class) {
            $this->app->bind("subscriptions.models." . $service,  $class);
        }

        // Registering commands

        if ($this->app->runningInConsole()) {
            $this->commands([
                SubscriptionsMigrateCommand::class,
            ]);
        }
    }
}
