<?php

namespace Glugox\Module\Contracts;

interface ManifestContract
{
    public function id(): string;

    public function name(): string;

    public function namespace(): string;

    public function description(): string;

    public function version(): string;

    /**
     * @return array<int, string>
     */
    public function capabilities(): array;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
