<?php

declare(strict_types=1);

use Capell\Core\Models\Language;
use Capell\Core\Models\Layout;
use Capell\Core\Models\Page;
use Capell\Frontend\Data\FrontendRenderContextData;
use Capell\Inertia\Actions\BuildInertiaPagePropsAction;
use Capell\Inertia\Actions\RenderInertiaResponseAction;
use Capell\Inertia\Actions\ResolveInertiaAdapterKeyAction;
use Capell\Inertia\Actions\ResolveInertiaComponentNameAction;
use Capell\Inertia\Actions\ResolveInertiaRootViewAction;
use Capell\Inertia\Data\InertiaAdapterData;
use Capell\Inertia\Support\InertiaAdapterRegistry;
use Capell\Inertia\Tests\InertiaTestCase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

require_once dirname(__DIR__) . '/InertiaTestCase.php';

uses(InertiaTestCase::class);

it('resolves configured inertia adapter keys with a stable fallback', function (): void {
    config()->set('capell-inertia.adapter', ' react ');

    expect(ResolveInertiaAdapterKeyAction::run())->toBe('react')
        ->and(ResolveInertiaAdapterKeyAction::run(' svelte '))->toBe('svelte')
        ->and(ResolveInertiaAdapterKeyAction::run(''))->toBe('vue')
        ->and(ResolveInertiaAdapterKeyAction::run(['invalid']))->toBe('vue');
});

it('resolves configured inertia root views with a stable fallback', function (): void {
    config()->set('capell-inertia.root_view', ' inertia-test::app ');
    config()->set('capell-inertia.allowed_root_views', ['capell-inertia::app', 'inertia-test::app']);

    expect(ResolveInertiaRootViewAction::run())->toBe('inertia-test::app')
        ->and(ResolveInertiaRootViewAction::run(' capell-custom::app '))->toBe('capell-inertia::app')
        ->and(ResolveInertiaRootViewAction::run(''))->toBe('capell-inertia::app')
        ->and(ResolveInertiaRootViewAction::run(['invalid']))->toBe('capell-inertia::app');
});

it('resolves configured inertia component names with a supplied fallback', function (): void {
    config()->set('capell-inertia.page_component', ' Capell/Configured ');

    expect(ResolveInertiaComponentNameAction::run())->toBe('Capell/Page')
        ->and(ResolveInertiaComponentNameAction::run(' Capell/Explicit '))->toBe('Capell/Explicit')
        ->and(ResolveInertiaComponentNameAction::run('', 'Capell/Fallback'))->toBe('Capell/Fallback')
        ->and(ResolveInertiaComponentNameAction::run(['invalid'], 'Capell/Fallback'))->toBe('Capell/Fallback');
});

it('builds public inertia page props without leaking authenticated user data', function (): void {
    config()->set('capell-inertia.adapter', ['invalid']);

    $props = BuildInertiaPagePropsAction::run(new FrontendRenderContextData(
        page: null,
        site: null,
        language: null,
        layout: null,
        theme: null,
    ));

    expect(data_get($props, 'runtime.adapter'))->toBe('vue')
        ->and(data_get($props, 'language.code'))->toBeNull()
        ->and(data_get($props, 'page'))->toBeArray()
        ->and($props)->not->toHaveKey('user')
        ->and(data_get($props, 'auth'))->toBeNull();
});

it('keeps the layout graph in page props within the API bounds', function (): void {
    $page = Page::factory()->create();
    $layout = Layout::factory()->site($page->site)->create([
        'key' => 'bounded',
        'containers' => ['main' => ['widgets' => []], 'sidebar' => ['widgets' => []]],
    ]);
    $context = new FrontendRenderContextData(
        page: $page,
        site: $page->site,
        language: Language::factory()->create(),
        layout: $layout,
        theme: null,
    );

    expect(data_get(BuildInertiaPagePropsAction::run($context), 'page.layout.containers'))->toHaveCount(2);

    config()->set('capell-api.public_pages.max_layout_containers', 1);

    expect(BuildInertiaPagePropsAction::run($context)['page'])->not->toHaveKey('layout');
});

it('renders sanitized inertia responses through the action', function (): void {
    View::addNamespace('inertia-test', __DIR__ . '/../Fixtures/views');
    config()->set('capell-inertia.root_view', ' inertia-test::app ');
    config()->set('capell-inertia.allowed_root_views', ['capell-inertia::app', 'inertia-test::app']);

    Route::middleware('web')->get('/_unit/inertia-action', fn (): Response => RenderInertiaResponseAction::run(
        component: ' Capell/Unit ',
        props: ['message' => 'ok'],
        status: 202,
    ));

    $this->get('/_unit/inertia-action', ['X-Inertia' => 'true'])
        ->assertStatus(202)
        ->assertJsonPath('component', 'Capell/Unit')
        ->assertJsonPath('props.message', 'ok');
});

it('uses the configured page component only when the active adapter registers that component name', function (): void {
    resolve(InertiaAdapterRegistry::class)->register(new InertiaAdapterData(
        key: 'vue',
        packageName: 'capell-app/inertia-vue-adapter',
        npmDependencies: [],
        buildPath: 'vendor/capell/inertia-vue',
        entrypoint: 'resources/js/app.js',
        components: ['page' => 'Capell/Page', 'landing' => 'Capell/Landing'],
    ));

    config()->set('capell-inertia.page_component', 'Capell/Landing');
    $registered = ResolveInertiaComponentNameAction::run();

    config()->set('capell-inertia.page_component', 'App/Unregistered');
    $unregistered = ResolveInertiaComponentNameAction::run();

    expect($registered)->toBe('Capell/Landing')
        ->and($unregistered)->toBe('Capell/Page');
});
