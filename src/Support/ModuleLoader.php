<?php

namespace Glugox\Module\Support;

use Glugox\Module\Contracts\HasAssets;
use Glugox\Module\Contracts\HasMigrations;
use Glugox\Module\Contracts\HasRoutes;
use Glugox\Module\Contracts\HasViews;
use Glugox\Module\Contracts\ModuleContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ModuleLoader
{
    public function __construct(
        protected readonly Application $app,
        protected readonly ServiceProvider $provider,
    ) {
    }

    public function register(ModuleContract $module): void
    {
        $this->registerServiceProvider($module);
        $this->registerRoutes($module);
        $this->registerMigrations($module);
        $this->registerViews($module);
        $this->registerAssets($module);
    }

    protected function registerServiceProvider(ModuleContract $module): void
    {
        $provider = $module->serviceProvider();

        if ($provider === null) {
            return;
        }

        $this->app->register($provider);
    }

    protected function registerRoutes(ModuleContract $module): void
    {
        if (! $module instanceof HasRoutes) {
            return;
        }

        $path = $module->routesPath();

        if ($path === null || ! is_file($path)) {
            return;
        }

        $this->provider->loadRoutesFrom($path);
    }

    protected function registerMigrations(ModuleContract $module): void
    {
        if (! $module instanceof HasMigrations) {
            return;
        }

        $path = $module->migrationsPath();

        if ($path === null || ! is_dir($path)) {
            return;
        }

        $this->provider->loadMigrationsFrom($path);
    }

    protected function registerViews(ModuleContract $module): void
    {
        if (! $module instanceof HasViews) {
            return;
        }

        $path = $module->viewsPath();

        if ($path === null || ! is_dir($path)) {
            return;
        }

        $namespace = $module->namespace();

        $this->provider->loadViewsFrom($path, $namespace);
    }

    protected function registerAssets(ModuleContract $module): void
    {
        if (! $module instanceof HasAssets) {
            return;
        }

        $path = $module->assetsPath();

        if ($path === null || ! is_dir($path)) {
            return;
        }

        $this->provider->publishes([
            $path => public_path('vendor/'.$module->id()),
        ], ['module-assets', $module->id().'::assets']);
    }
}
