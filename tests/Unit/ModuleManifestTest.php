<?php

use Glugox\Module\Support\ModuleManifest;

test('module manifest exposes metadata', function () {
    $manifest = new ModuleManifest(
        id: 'vendor/example',
        name: 'Example',
        namespace: 'Vendor\\Example',
        description: 'Demo module',
        version: '1.2.3',
        capabilities: ['http:web', 'http:api'],
    );

    expect($manifest->id())->toBe('vendor/example');
    expect($manifest->name())->toBe('Example');
    expect($manifest->namespace())->toBe('Vendor\\Example');
    expect($manifest->description())->toBe('Demo module');
    expect($manifest->version())->toBe('1.2.3');
    expect($manifest->capabilities())->toBe(['http:web', 'http:api']);
    expect($manifest->toArray())->toBe([
        'id' => 'vendor/example',
        'name' => 'Example',
        'namespace' => 'Vendor\\Example',
        'description' => 'Demo module',
        'version' => '1.2.3',
        'capabilities' => ['http:web', 'http:api'],
    ]);
});

test('module manifest can be created from array payload', function () {
    $manifest = ModuleManifest::fromArray([
        'id' => 'vendor/blog',
        'name' => 'Blog',
        'namespace' => 'Vendor\\Blog',
        'description' => 'Blog module',
        'version' => '0.1.0',
        'capabilities' => ['http:web'],
    ]);

    expect($manifest->toArray())
        ->toHaveKey('name', 'Blog')
        ->toHaveKey('capabilities', ['http:web']);
});
