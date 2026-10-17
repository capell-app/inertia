<?php

declare(strict_types=1);

namespace Capell\Inertia\Providers;

use Capell\Core\Enums\FrontendRuntime;
use Capell\Core\Facades\CapellCore;
use Capell\Core\Support\Packages\AbstractPackageServiceProvider;
use Capell\Frontend\Support\Render\FrontendResponseRendererRegistry;
use Capell\Frontend\Support\Routing\FrontendRouteMiddlewareRegistry;
use Capell\Inertia\Http\Middleware\HandleInertiaRequests;
use Capell\Inertia\Rendering\CapellInertiaResponseRenderer;
use Capell\Inertia\Support\CapellInertiaManager;
use Capell\Inertia\Support\InertiaAdapterRegistry;
use Illuminate\Support\Facades\Route;
use Inertia\ServiceProvider as LaravelInertiaServiceProvider;
use Override;
use Spatie\LaravelPackageTools\Package;

final class InertiaServiceProvider extends AbstractPackageServiceProvider
{
    public static string $name = 'capell-inertia';

    public static string $packageName = 'capell-app/inertia';

    #[Override]
    public function configurePackage(Package $package): void
    {
        $package
            ->name(self::$name)
            ->hasConfigFile()
            ->hasViews(self::$name);
    }

    #[Override]
    public function packageRegistered(): void
    {
        if (class_exists(LaravelInertiaServiceProvider::class) && $this->app->getProvider(LaravelInertiaServiceProvider::class) === null) {
            $this->app->register(LaravelInertiaServiceProvider::class);
        }

        $this->app->singleton('capell-inertia', fn (): CapellInertiaManager => new CapellInertiaManager);
        $this->app->singleton(InertiaAdapterRegistry::class);
    }

    #[Override]
    public function packageBooted(): void
    {
        Route::aliasMiddleware('capell.inertia', HandleInertiaRequests::class);
    }

    #[Override]
    protected function bootInstalledRuntime(): void
    {
        $this->registerFrontendMiddleware();
        $this->registerRenderer();
    }

    #[Override]
    protected function isPackageInstalled(): bool
    {
        return CapellCore::isPackageInstalled(self::$packageName);
    }

    private function registerRenderer(): void
    {
        $configure = static function (FrontendResponseRendererRegistry $registry): void {
            $registry->registerClass(FrontendRuntime::Inertia, CapellInertiaResponseRenderer::class);
        };

        $this->callAfterResolving(FrontendResponseRendererRegistry::class, $configure);
    }

    private function registerFrontendMiddleware(): void
    {
        $configure = static function (FrontendRouteMiddlewareRegistry $registry): void {
            $registry->insertAfter('web', [HandleInertiaRequests::class]);
            // Frontend's boot callback has already built this group during late installation.
            Route::middlewareGroup('capell-frontend', $registry->all());
        };

        $this->callAfterResolving(FrontendRouteMiddlewareRegistry::class, $configure);
    }
}
