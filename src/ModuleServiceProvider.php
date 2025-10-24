<?php

namespace Glugox\Module;

use Glugox\Module\Contracts\ModuleContract;
use Glugox\Module\Support\ModuleLoader;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class ModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/module.php', 'module');

        $this->app->singleton(ModuleLoader::class, function ($app) {
            return new ModuleLoader($app, $this);
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/module.php' => config_path('module.php'),
        ], 'config');

        $this->registerConfiguredModules();
    }

    protected function registerConfiguredModules(): void
    {
        $modules = config('module.modules', []);

        foreach (Arr::wrap($modules) as $moduleClass) {
            $module = $this->instantiateModule($moduleClass);

            $this->app->make(ModuleLoader::class)->register($module);
        }
    }

    protected function instantiateModule(string $moduleClass): ModuleContract
    {
        $module = $this->app->make($moduleClass);

        if (! $module instanceof ModuleContract) {
            throw new InvalidArgumentException(sprintf(
                'Module class [%s] must implement %s.',
                $moduleClass,
                ModuleContract::class,
            ));
        }

        return $module;
    }

    /**
     * Register the route definitions exposed by the given module.
     */
    public function registerModuleRoutes(string $path): void
    {
        $this->loadRoutesFrom($path);
    }

    /**
     * Register database migrations exposed by the given module.
     */
    public function registerModuleMigrations(string $path): void
    {
        $this->loadMigrationsFrom($path);
    }

    /**
     * Register the view namespace exposed by the given module.
     */
    public function registerModuleViews(string $path, string $namespace): void
    {
        $this->loadViewsFrom($path, $namespace);
    }

    /**
     * Register publishable assets exposed by the given module.
     */
    public function registerModuleAssets(string $path, string $moduleId): void
    {
        $this->publishes([
            $path => public_path('vendor/'.$moduleId),
        ], ['module-assets', $moduleId.'::assets']);
    }
}
