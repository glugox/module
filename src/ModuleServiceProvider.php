<?php

namespace Glugox\Module;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->publishes([
        __DIR__.'/../config/module.php' => config_path('module.php'),
    ], 'config');
    }
}
