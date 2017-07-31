<?php

/**
 * Ping Service Provider
 *
 * @author Harsha vardhan (harshaaliaschinna@gmail.com)
 *
 * @version 0.1
 */
namespace Harshaaliaschinna\Ping;

use Illuminate\Support\ServiceProvider;
use Harshaaliaschinna\Ping\PingContract;
use Harshaaliaschinna\Ping\PingFacade;
use Harshaaliaschinna\Ping\Ping;

class PingServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->migrations();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindings();
        // $this->aliases();
    }

    /**
     * Binding to Service Container.
     *
     * @return void
     */
    public function bindings()
    {
        $this->app->singleton([PingContract::class => 'Ping'], Ping::class);
    }

    /**
     * Registering aliases.
     *
     * @return void
     */
    public function aliases()
    {
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Ping', PingFacade::class);
        });
    }

    /**
     * Publishes the Migrations.
     *
     * @return void
     */
    public function migrations()
    {
        $this->publishes([
            realpath(__DIR__.'/../migrations/') => database_path('migrations'),
        ], 'migrations');
    }

}
