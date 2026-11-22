# Capell Inertia

<!-- prettier-ignore-start -->

A development team that builds in Vue or React keeps its JavaScript stack, and Capell pages reach visitors through that front end instead of the site's usual templates. This package sends them there. Editors keep working in the Capell admin and see nothing different.

You pick one adapter to go with it, the Vue adapter or the React adapter. Vue is the default, and a React site sets CAPELL_INERTIA_ADAPTER to react. You also supply a theme set up to show pages through Inertia and you run the front-end build. The package itself contains no JavaScript.

Each page goes to the browser with its title and content, its meta tags, its language and the layout's containers of widgets. Content, image and title widgets each carry a component name for the front end to use, and other widget types carry none. The page shell the front end mounts into must come from a short allowed list of views, and every HTML response is checked for editing leftovers before it leaves. A Diagnostics health check confirms that the page renderer, the request handling and the chosen adapter are registered.

## What it does

Capell Inertia is the shared public-rendering bridge for Inertia-powered Capell themes and package routes. It registers the Inertia renderer, frontend middleware, safe public page props, root view, and adapter registry.

## Do I need to do anything?

Editors usually do nothing day to day. Integrators must install a matching adapter package, such as the Vue or React adapter, and configure the active adapter with `CAPELL_INERTIA_ADAPTER` (default: `vue`). Check Diagnostics if the configured adapter is not registered.

## Where it shows up

There is no package-owned admin screen or setting. It runs on public frontend routes for Inertia-enabled themes and package pages, using the configured root view and `Capell/Page` component contract.

## Good to know

- Install this only when a consuming theme or package uses the Inertia frontend runtime.
- The active adapter must be registered by a matching adapter package; the bridge does not ship Vue or React application components itself.
- Configuration is sanitised: invalid adapter or root-view values fall back to the registered safe defaults. `capell-inertia.page_component` is used only when the active adapter registers a component with that name, otherwise `Capell/Page` is used.
- Every response shares one prop, `capell.adapter`. Each page response adds `page`, `language` and `runtime.adapter`; there is no site prop, and no authenticated user or authoring data is sent.
- Image widgets carry only a title and content unless a package supplies more through a layout widget payload contributor; the adapters' image component draws an image only when the payload includes `data.image.url`.
- Other packages render their own routes through the `CapellInertia` facade, naming a component the active adapter provides. The adapters register `Capell/Bookings/Request` for a bookings package to use.

---

For developers: see the [README](../README.md).

<!-- prettier-ignore-end -->
