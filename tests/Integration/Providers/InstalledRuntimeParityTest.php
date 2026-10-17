<?php

declare(strict_types=1);

use Capell\Core\Actions\InstallPackageAction;
use Capell\Core\Actions\RuntimeRefresh\RefreshInstalledPackageRuntimeAction;
use Capell\Core\Data\Runtime\RuntimeRoleSelectionData;
use Capell\Core\Enums\FrontendRuntime;
use Capell\Core\Enums\RuntimeRole;
use Capell\Core\Facades\CapellCore;
use Capell\Core\Support\Runtime\RuntimeRoleResolver;
use Capell\Frontend\Support\Render\FrontendResponseRendererRegistry;
use Capell\Frontend\Support\Routing\FrontendRouteMiddlewareRegistry;
use Capell\Inertia\Http\Middleware\HandleInertiaRequests;
use Capell\Tests\Support\InstalledRuntimeParity;
use Capell\Tests\Support\InstalledRuntimeState;
use Capell\Tests\Support\InstalledRuntimeTestCase;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;

it('matches fresh installed boot after in-process installation and repeated refresh', function (): void {
    InstalledRuntimeParity::assertPackage('inertia');
});

it('keeps the renderer and ordered middleware available in the public role', function (bool $installed): void {
    InstalledRuntimeState::run(static function () use ($installed): void {
        $app = InstalledRuntimeTestCase::boot(InstalledRuntimeParity::manifest('inertia'), $installed, static function (Application $app): void {
            $app->instance(RuntimeRoleResolver::class, new RuntimeRoleResolver(RuntimeRoleSelectionData::fromConfiguredValue('public')));
        }, sourceRoot: dirname(__DIR__, 3));
        expect($app->make(RuntimeRoleResolver::class)->role())->toBe(RuntimeRole::Public);
        $renderers = $app->make(FrontendResponseRendererRegistry::class);
        $middleware = $app->make(FrontendRouteMiddlewareRegistry::class);
        $assertRuntime = static function (bool $available) use ($app, $renderers, $middleware): void {
            expect($renderers->has(FrontendRuntime::Inertia))->toBe($available);
            $stack = $middleware->all();
            expect(array_filter($stack, static fn (string $entry): bool => $entry === HandleInertiaRequests::class))->toHaveCount($available ? 1 : 0);
            if ($available) {
                expect($app->make(Router::class)->getMiddlewareGroups()['capell-frontend'])->toBe($stack);
                expect(array_search(HandleInertiaRequests::class, $stack, true))->toBe(array_search('web', $stack, true) + 1);
            }
        };
        $assertRuntime($installed);
        $package = CapellCore::getPackage('capell-app/inertia');
        if (! $installed) {
            InstalledRuntimeTestCase::prepareLateInstallMigrations($app);
            InstallPackageAction::run($package);
        }
        $assertRuntime(true);
        RefreshInstalledPackageRuntimeAction::run($package);
        RefreshInstalledPackageRuntimeAction::run($package);
        $assertRuntime(true);
    });
})->with([true, false]);
