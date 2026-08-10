<?php

declare(strict_types=1);

namespace Capell\Inertia\Tests\Fixtures\Inertia;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Middleware
{
    /** @var string */
    protected $rootView = 'app';

    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [];
    }

    public function rootView(Request $request): string
    {
        return $this->rootView;
    }
}
