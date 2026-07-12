<?php

declare(strict_types=1);

return [
    'adapter' => env('CAPELL_INERTIA_ADAPTER', 'vue'),
    'root_view' => 'capell-inertia::app',
    'allowed_root_views' => ['capell-inertia::app'],
    'page_component' => 'Capell/Page',
];
