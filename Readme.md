# glugox/module – Documentation

## Introduction

The `glugox/module` package defines the **foundation for modular development** in Laravel. It introduces the common abstractions, contracts, and base classes that every module must use. This ensures consistency across all modules and makes it possible for tools like `glugox/orchestrator` and `glugox/module-generator` to integrate seamlessly.

---

## Key Concepts

### 1. Module Abstraction

* A module is a self-contained package that adds functionality to a Laravel app.
* Every module must implement the `ModuleContract` or extend the abstract `Module` base class.
* A module contains its own routes, migrations, views, assets, and service providers.

### 2. Module Manifest

* The manifest describes the metadata of a module.
* It includes: `id`, `name`, `namespace`, `description`, `version`, and `capabilities`.
* It allows orchestrators to discover and load modules without knowing implementation details.

### 3. Contracts

Located in `src/Contracts`, they enforce module capabilities:

* `ModuleContract` → defines required metadata methods.
* `HasRoutes`, `HasMigrations`, `HasViews`, `HasAssets` → optional feature interfaces.
* `ManifestContract` → for objects that expose manifest data.

### 4. Support Classes

* `ModuleManifest` → value object holding module metadata.
* `ModuleLoader` → utility to bootstrap and resolve modules.

### 5. Service Provider Integration

* `ModuleServiceProvider` ensures that modules can register services into Laravel’s container and lifecycle.

---

## Package Structure

```
glugox/module/
├── src/
│   ├── Contracts/
│   │   ├── ModuleContract.php
│   │   ├── ManifestContract.php
│   │   ├── HasRoutes.php
│   │   ├── HasViews.php
│   │   ├── HasMigrations.php
│   │   └── HasAssets.php
│   ├── Support/
│   │   ├── ModuleManifest.php
│   │   └── ModuleLoader.php
│   ├── Module.php
│   └── ModuleServiceProvider.php
└── composer.json
```

---

## Example Implementation

### Defining a Module

```php
use Glugox\Module\Module;
use App\Providers\BillingServiceProvider;

class BillingModule extends Module
{
    public function id(): string { return 'company/billing'; }
    public function name(): string { return 'Billing'; }
    public function description(): string { return 'Invoices and payments'; }
    public function version(): string { return '1.0.0'; }
    public function capabilities(): array { return ['http:web', 'http:api']; }

    public function serviceProvider(): string
    {
        return BillingServiceProvider::class;
    }

    public function routesPath(): ?string
    {
        return __DIR__ . '/routes/web.php';
    }
}
```

### Example Manifest

```json
{
  "id": "company/billing",
  "name": "Billing",
  "namespace": "Company\\Billing",
  "description": "Invoices and payments",
  "version": "1.0.0",
  "capabilities": ["http:web", "http:api"]
}
```

---

## How It Fits in the Ecosystem

* **`glugox/module-generator`** creates modules that extend the `Module` base class.
* **`glugox/orchestrator`** loads modules by reading their manifests and registering providers.
* **Main Laravel App** → simply requires modules as composer packages and lets orchestrator manage them.

---

## Benefits

* **Standardization** → Every module follows the same pattern.
* **Reusability** → Modules can be reused across multiple Laravel projects.
* **Separation of Concerns** → Clear split between contracts (in `glugox/module`), orchestration (in `glugox/orchestrator`), and generation (in `glugox/module-generator`).

---

## Next Steps

* Finalize the `ModuleContract` and optional feature interfaces.
* Improve `ModuleLoader` to handle different discovery strategies (filesystem, composer).
* Document how modules interact with Laravel’s container and lifecycle in detail.
