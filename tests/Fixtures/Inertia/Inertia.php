<?php

declare(strict_types=1);

namespace Capell\Inertia\Tests\Fixtures\Inertia;

final class Inertia
{
    public static string $rootView = 'app';

    public static function setRootView(string $rootView): void
    {
        self::$rootView = $rootView;
    }

    public static function optional(callable $callback): callable
    {
        return $callback;
    }

    /**
     * @param  array<string, mixed>  $props
     */
    public static function render(string $component, array $props = []): InertiaResponse
    {
        return new InertiaResponse($component, $props);
    }
}
