<?php
namespace Sdas\Changelog;

use Illuminate\Support\ServiceProvider;

class ChangeLogServiceProvider extends ServiceProvider 
{
    public function boot() 
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/views', 'changelog');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations'); 
        $this->mergeConfigFrom( __DIR__.'/config/changelog.php', 'changelog' );

        $this->publishes([
            __DIR__.'/config/changelog.php' => config_path('changelog.php')
        ], 'changelog-config');

        $this->publishes([
            __DIR__.'/database/migrations/' => database_path('migrations')
        ], 'changelog-migrations');
    }

    public function register()
    {

    }
}