<?php

namespace Smartech\Subscriptions;

use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

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
            $this->basePath . '/config/' => config_path(),
        ], "subscriptions.config");

        // Load translations files

        $this->loadTranslationsFrom($this->basePath . "/resources/lang", "subscriptions");

        // Auto configuration with lumen framework.

        if (Str::contains($this->app->version(), 'Lumen')) {
            $this->app->configure("subscriptions");
        }
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
            $this->app->singleton("subscriptions.models." . $service,  $class);
        }
    }
}
