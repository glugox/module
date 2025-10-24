<?php

use Glugox\Module\Module;

it('creates manifest with default capabilities', function () {
    $module = new class() extends Module {
        public function id(): string
        {
            return 'vendor/example';
        }

        public function name(): string
        {
            return 'Example';
        }

        public function namespace(): string
        {
            return 'Vendor\\Example';
        }

        public function description(): string
        {
            return 'Demo';
        }

        public function version(): string
        {
            return '1.0.0';
        }
    };

    $manifest = $module->manifest();

    expect($manifest->toArray())->toBe([
        'id' => 'vendor/example',
        'name' => 'Example',
        'namespace' => 'Vendor\\Example',
        'description' => 'Demo',
        'version' => '1.0.0',
        'capabilities' => [],
    ]);
});

it('can override optional hooks', function () {
    $module = new class() extends Module {
        public function id(): string { return 'vendor/blog'; }
        public function name(): string { return 'Blog'; }
        public function namespace(): string { return 'Vendor\\Blog'; }
        public function description(): string { return 'Blog module'; }
        public function version(): string { return '0.1.0'; }
        public function capabilities(): array { return ['http:web']; }
        public function serviceProvider(): ?string { return 'App\\Providers\\BlogServiceProvider'; }
        public function routesPath(): ?string { return __FILE__; }
        public function migrationsPath(): ?string { return __DIR__; }
        public function viewsPath(): ?string { return __DIR__; }
        public function assetsPath(): ?string { return __DIR__; }
    };

    expect($module->capabilities())->toBe(['http:web']);
    expect($module->serviceProvider())->toBe('App\\Providers\\BlogServiceProvider');
    expect($module->routesPath())->toBe(__FILE__);
    expect($module->migrationsPath())->toBe(__DIR__);
    expect($module->viewsPath())->toBe(__DIR__);
    expect($module->assetsPath())->toBe(__DIR__);
});
