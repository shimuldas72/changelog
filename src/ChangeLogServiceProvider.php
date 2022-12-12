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
    }

    public function register()
    {

    }
}