<?php

declare(strict_types=1);

namespace Capell\Inertia\Actions;

use Capell\Inertia\Support\InertiaAdapterRegistry;
use Lorisleiva\Actions\Concerns\AsFake;
use Lorisleiva\Actions\Concerns\AsObject;

/**
 * @method static string run(mixed $configuredComponent = null, string $fallback = self::DefaultPageComponent)
 */
final class ResolveInertiaComponentNameAction
{
    use AsFake;
    use AsObject;

    public const string DefaultPageComponent = 'Capell/Page';

    public function handle(mixed $configuredComponent = null, string $fallback = self::DefaultPageComponent): string
    {
        $usesConfiguredDefault = $configuredComponent === null;
        $component = $configuredComponent ?? config('capell-inertia.page_component', $fallback);

        if (! is_string($component)) {
            return $fallback;
        }

        $component = trim($component);

        if ($component === '') {
            return $fallback;
        }

        if (! $usesConfiguredDefault) {
            return $component;
        }

        $adapter = resolve(InertiaAdapterRegistry::class)->active();

        return $adapter !== null && array_key_exists($component, $adapter->components)
            ? $component
            : $fallback;
    }
}
