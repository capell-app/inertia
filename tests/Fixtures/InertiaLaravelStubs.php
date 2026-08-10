<?php

declare(strict_types=1);

namespace Capell\Inertia\Tests\Fixtures;

use Capell\Inertia\Tests\Fixtures\Inertia\Inertia as TestInertia;
use Capell\Inertia\Tests\Fixtures\Inertia\InertiaResponse as TestInertiaResponse;
use Capell\Inertia\Tests\Fixtures\Inertia\Middleware as TestMiddleware;
use Inertia\Inertia;
use Inertia\Middleware;
use Inertia\Response;

require_once __DIR__ . '/Inertia/Inertia.php';
require_once __DIR__ . '/Inertia/InertiaResponse.php';
require_once __DIR__ . '/Inertia/Middleware.php';

if (! class_exists(Middleware::class, false)) {
    class_alias(TestMiddleware::class, Middleware::class);
}

if (! class_exists(Inertia::class, false)) {
    class_alias(TestInertia::class, Inertia::class);
}

if (! class_exists(Response::class, false)) {
    class_alias(TestInertiaResponse::class, Response::class);
}
