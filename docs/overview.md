# Capell Inertia

<!-- prettier-ignore-start -->

## What it does

Capell Inertia is the shared public-rendering bridge for Inertia-powered Capell themes and package routes. It registers the Inertia renderer, frontend middleware, safe public page props, root view, and adapter registry.

## Do I need to do anything?

Editors usually do nothing day to day. Integrators must install a matching adapter package, such as the Vue or React adapter, and configure the active adapter with `CAPELL_INERTIA_ADAPTER` (default: `vue`). Check Diagnostics if the configured adapter is not registered.

## Where it shows up

There is no package-owned admin screen or setting. It runs on public frontend routes for Inertia-enabled themes and package pages, using the configured root view and `Capell/Page` component contract.

## Good to know

- Install this only when a consuming theme or package uses the Inertia frontend runtime.
- The active adapter must be registered by a matching adapter package; the bridge does not ship Vue or React application components itself.
- Configuration is sanitised: invalid adapter, root-view, or component values fall back to the registered safe defaults.
- Shared public props include site, language, page, runtime, and adapter context, but not authenticated user or authoring data.

---

For developers: see the [README](../README.md).

<!-- prettier-ignore-end -->
