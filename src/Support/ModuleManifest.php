<?php

namespace Glugox\Module\Support;

use Glugox\Module\Contracts\ManifestContract;
use JsonSerializable;

class ModuleManifest implements ManifestContract, JsonSerializable
{
    /**
     * @param array<int, string> $capabilities
     */
    public function __construct(
        protected readonly string $id,
        protected readonly string $name,
        protected readonly string $namespace,
        protected readonly string $description,
        protected readonly string $version,
        protected readonly array $capabilities = [],
    ) {
    }

    public static function fromArray(array $attributes): self
    {
        return new self(
            id: $attributes['id'],
            name: $attributes['name'],
            namespace: $attributes['namespace'],
            description: $attributes['description'],
            version: $attributes['version'],
            capabilities: $attributes['capabilities'] ?? [],
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function namespace(): string
    {
        return $this->namespace;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function version(): string
    {
        return $this->version;
    }

    /**
     * @return array<int, string>
     */
    public function capabilities(): array
    {
        return $this->capabilities;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'namespace' => $this->namespace(),
            'description' => $this->description(),
            'version' => $this->version(),
            'capabilities' => $this->capabilities(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
