# Capell Inertia reference

<!-- prettier-ignore-start -->

Developer reference for `capell-app/inertia`. Start with the [README](../README.md) for what the extension does and how to install it.

## Package Details

Capell Inertia is an **Available** Capell extension in the **Capell Frontend** product group. It ships as `capell-app/inertia` and extends these surfaces: frontend.

- Status: Available
- Tier: core
- Bundle: frontend
- Composer package: `capell-app/inertia`
- Namespace: `Capell\Inertia`
- Theme key: not applicable

## Technical Shape

### Service providers

- `Capell\Inertia\Providers\InertiaServiceProvider`

### Config files

- `packages/inertia/config/capell-inertia.php`

### Actions

- `BuildInertiaPagePropsAction`
- `RenderInertiaResponseAction`
- `ResolveInertiaAdapterKeyAction`
- `ResolveInertiaComponentNameAction`
- `ResolveInertiaRootViewAction`

### Data objects

- `InertiaAdapterData`

### Health checks

- `Capell\Inertia\Health\InertiaHealthCheck`

### Blade views

- `packages/inertia/resources/views/app.blade.php`


## Data Model

This package has no schema impact. It registers runtime behaviour through `Capell\Inertia\Providers\InertiaServiceProvider` while persistence remains with Capell core or required packages.

<!-- prettier-ignore-end -->
