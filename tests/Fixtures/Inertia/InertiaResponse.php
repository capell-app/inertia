<?php

declare(strict_types=1);

namespace Capell\Inertia\Tests\Fixtures\Inertia;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class InertiaResponse
{
    /**
     * @param  array<string, mixed>  $props
     */
    public function __construct(
        private readonly string $component,
        private readonly array $props,
    ) {}

    public function toResponse(Request $request): Response
    {
        if ($request->headers->get('X-Inertia') !== 'true') {
            $page = [
                'component' => $this->component,
                'props' => $this->props,
            ];

            return new Response(
                '<div id="app" data-page="' . htmlspecialchars(json_encode($page, JSON_THROW_ON_ERROR), ENT_QUOTES, 'UTF-8') . '"></div>',
                Response::HTTP_OK,
                ['Content-Type' => 'text/html; charset=UTF-8'],
            );
        }

        return new JsonResponse([
            'component' => $this->component,
            'props' => $this->props,
        ]);
    }
}
