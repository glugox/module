<?php

namespace Glugox\Module\Support;

use Glugox\Module\Contracts\HasAssets;
use Glugox\Module\Contracts\HasMigrations;
use Glugox\Module\Contracts\HasRoutes;
use Glugox\Module\Contracts\HasViews;
use Glugox\Module\Contracts\ModuleContract;
use Illuminate\Contracts\Foundation\Application;
use Glugox\Module\ModuleServiceProvider;

class ModuleLoader
{
    public function __construct(
        protected readonly Application $app,
        protected readonly ModuleServiceProvider $provider,
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

        $this->provider->registerModuleRoutes($path);

        $this->app['router']->getRoutes()->refreshNameLookups();
        $this->app['router']->getRoutes()->refreshActionLookups();
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

        $this->provider->registerModuleMigrations($path);
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

        $this->provider->registerModuleViews($path, $namespace);
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

        $this->provider->registerModuleAssets($path, $module->id());
    }
}
