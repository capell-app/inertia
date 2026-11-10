# Capell Inertia

<!-- prettier-ignore-start -->

## What It Does

Capell Inertia connects public page resolution to Inertia responses through a registered client adapter.

- **Price and availability:** Check current availability and price on the [Capell Marketplace listing](https://capell.app/extensions/inertia).
- **Licence:** Check the licence terms on the [Capell Marketplace listing](https://capell.app/extensions/inertia).
- **Requires:** `PHP ^8.4`, `capell-app/api ^1.0`, `capell-app/core ^1.0.66`, `capell-app/frontend ^1.0`, `inertiajs/inertia-laravel ^3.0`, `lorisleiva/laravel-actions ^2.8`, `spatie/laravel-package-tools ^1.14.1`.
- **Install:** `composer require capell-app/inertia`.

It adds no admin screen. When an Inertia theme is active, public pages use the configured root view, component name, and adapter.

## Who It Is For

**For developers:** The adapter registry and typed Actions centralize page props, component resolution, and response rendering for Inertia themes.

**For teams:** Teams can use React or Vue Inertia themes while keeping the normal Capell content workflow.

## Quick Start

1. Install the package: `composer require capell-app/inertia`.
2. Verify the package provider and manifest contributions are registered in the host app.

## Configure

- **Configuration files:** `config/capell-inertia.php`.

## Limits And Pitfalls

- Install the required Capell packages at the versions in `composer.json`: `capell-app/api ^1.0`, `capell-app/core ^1.0.66`, `capell-app/frontend ^1.0`.
- Keep public Blade and cached HTML free of authoring markers, model IDs, permissions, signed editor URLs, and lazy database queries.
- Custom write integrations must preserve invalidation for `inertia` cache tags.

## Screens And Workflow

![Illustrative inertia catalogue card](docs/screenshots/inertia-catalogue-preview.png)

- Illustrative inertia catalogue card (frontend).

## Install Impact

- Required packages: `capell-app/core`, `capell-app/frontend`, `capell-app/api`.
- Admin navigation: no admin page or resource contribution is declared.
- Admin/editor extensions: none declared.
- Permissions: no package permission declarations or Shield gates detected; host access rules still apply.
- Public routes: none declared.
- Database changes: no package migrations declared.
- Config: `config/capell-inertia.php`.
- Settings: no package settings declared.
- Queues or schedules: none declared.
- Cache tags: `inertia`.
- Commands: none declared.

## Troubleshooting

| Symptom | Likely cause | Check | Fix |
| --- | --- | --- | --- |
| Package surface is missing after install | Provider or manifest is not loaded | Confirm `capell.json`, package `composer.json`, and provider registration | Reinstall the package, refresh Composer autoload, and clear host caches |
| Public output leaks unexpected state | Render data, cache variation, or authoring boundary has regressed | Check public Blade, cache tags, and public-output safety tests | Move data loading out of Blade and rerun the package public-output tests |

## Upgrading

No manual steps between minor versions.

## Next Steps

- [Package docs](docs/README.md)
- [Overview](docs/overview.md)
- Configuration files: [`config/capell-inertia.php`](config/capell-inertia.php).
- [Troubleshooting](#troubleshooting)
- [Developer reference](docs/reference.md)
- [Marketplace assets](docs/assets/marketplace/)
- [Capell content language plan](../../docs/CONTENT_LANGUAGE_PLAN.md)
- [Capell documentation design system](../../docs/DESIGN_SYSTEM.md)
- [Capell and package ERD notes](../../docs/erd/capell-and-package-erds.md)
- Related packages: [API](../api/README.md), [Layout Builder](../layout-builder/README.md).

<!-- prettier-ignore-end -->
