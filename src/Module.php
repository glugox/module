<?php

namespace Glugox\Module;

use Glugox\Module\Contracts\ModuleContract;
use Glugox\Module\Contracts\ManifestContract;
use Glugox\Module\Support\ModuleManifest;

abstract class Module implements ModuleContract
{
    abstract public function id(): string;

    abstract public function name(): string;

    abstract public function namespace(): string;

    abstract public function description(): string;

    abstract public function version(): string;

    /**
     * @return array<int, string>
     */
    public function capabilities(): array
    {
        return [];
    }

    public function serviceProvider(): ?string
    {
        return null;
    }

    public function routesPath(): ?string
    {
        return null;
    }

    public function migrationsPath(): ?string
    {
        return null;
    }

    public function viewsPath(): ?string
    {
        return null;
    }

    public function assetsPath(): ?string
    {
        return null;
    }

    public function manifest(): ManifestContract
    {
        return new ModuleManifest(
            id: $this->id(),
            name: $this->name(),
            namespace: $this->namespace(),
            description: $this->description(),
            version: $this->version(),
            capabilities: $this->capabilities(),
        );
    }
}
