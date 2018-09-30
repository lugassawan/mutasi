<?php

namespace Lugasdev\Mutasi;

use Illuminate\Support\ServiceProvider;

class MutasiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/mutasi.php' => config_path('mutasi.php'),
        ], 'config');

        //load migrations
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //config
        $this->mergeConfigFrom(__DIR__.'/Config/mutasi.php', 'mutasi');

        $this->app->bind('lugasdev-mutasi', function(){
            return new Mutasi();
        });
    }
}