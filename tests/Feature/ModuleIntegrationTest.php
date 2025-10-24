<?php

use Glugox\Module\Module;
use Glugox\Module\ModuleServiceProvider;
use Glugox\Module\Contracts\HasAssets;
use Glugox\Module\Contracts\HasMigrations;
use Glugox\Module\Contracts\HasRoutes;
use Glugox\Module\Contracts\HasViews;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

it('registers configured modules through the service provider', function () {
    $filesystem = new Filesystem();
    $basePath = storage_path('framework/testing/module-fixture');

    if ($filesystem->isDirectory($basePath)) {
        $filesystem->deleteDirectory($basePath);
    }

    $filesystem->makeDirectory($basePath, 0777, true);

    $routesPath = $basePath.'/routes.php';
    file_put_contents($routesPath, <<<'PHP'
<?php

use Illuminate\Support\Facades\Route;

Route::get('/module-ping', fn () => 'pong')->name('module.ping');
PHP);

    $viewsPath = $basePath.'/views';
    $filesystem->makeDirectory($viewsPath);
    file_put_contents($viewsPath.'/ping.blade.php', 'pong');

    $migrationsPath = $basePath.'/database/migrations';
    $filesystem->makeDirectory($migrationsPath, 0777, true);

    $assetsPath = $basePath.'/public';
    $filesystem->makeDirectory($assetsPath);

    config()->set('module.modules', [TestModule::class]);

    $this->app->bind(TestModule::class, fn () => new TestModule($basePath));

    $provider = new ModuleServiceProvider($this->app);
    $provider->register();
    $provider->boot();

    $route = Route::getRoutes()->getByName('module.ping');
    expect($route)->not()->toBeNull();
    expect($this->app->make('module.test.service'))->toBe('resolved');

    expect(View::make('tests::ping')->render())->toBe('pong');

    $paths = $this->app->make('migrator')->paths();
    expect($paths)->toContain($migrationsPath);

    $assetPublishes = ServiceProvider::pathsToPublish(ModuleServiceProvider::class, 'module-assets');
    expect($assetPublishes)
        ->toHaveKey($assetsPath)
        ->and($assetPublishes[$assetsPath])
        ->toBe(public_path('vendor/tests/module'));

    $moduleAssetPublishes = ServiceProvider::pathsToPublish(ModuleServiceProvider::class, 'tests/module::assets');
    expect($moduleAssetPublishes)
        ->toHaveKey($assetsPath)
        ->and($moduleAssetPublishes[$assetsPath])
        ->toBe(public_path('vendor/tests/module'));

    $filesystem->deleteDirectory($basePath);
});

class TestModule extends Module implements HasRoutes, HasViews, HasMigrations, HasAssets
{
    public function __construct(private readonly string $basePath)
    {
    }

    public function id(): string
    {
        return 'tests/module';
    }

    public function name(): string
    {
        return 'Tests Module';
    }

    public function namespace(): string
    {
        return 'tests';
    }

    public function description(): string
    {
        return 'Testing module';
    }

    public function version(): string
    {
        return '1.0.0';
    }

    public function capabilities(): array
    {
        return ['http:web'];
    }

    public function serviceProvider(): ?string
    {
        return TestModuleServiceProvider::class;
    }

    public function routesPath(): ?string
    {
        return $this->basePath.'/routes.php';
    }

    public function migrationsPath(): ?string
    {
        return $this->basePath.'/database/migrations';
    }

    public function viewsPath(): ?string
    {
        return $this->basePath.'/views';
    }

    public function assetsPath(): ?string
    {
        return $this->basePath.'/public';
    }
}

class TestModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('module.test.service', fn () => 'resolved');
    }
}
