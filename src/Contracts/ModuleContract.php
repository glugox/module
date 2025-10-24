<?php

namespace Glugox\Module\Contracts;

interface ModuleContract extends ManifestContract
{
    public function serviceProvider(): ?string;

    public function routesPath(): ?string;

    public function migrationsPath(): ?string;

    public function viewsPath(): ?string;

    public function assetsPath(): ?string;

    public function manifest(): ManifestContract;
}
