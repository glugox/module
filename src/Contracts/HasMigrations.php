<?php

namespace Glugox\Module\Contracts;

interface HasMigrations
{
    public function migrationsPath(): ?string;
}
