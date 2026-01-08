<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LinkController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $links = Link::query()
            ->when($request->input('search'), function ($q, $search) {
                $q->where('short_slug', 'like', "%{$search}%")
                  ->orWhere('target_url', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate($request->input('per_page', 15));

        return LinkResource::collection($links);
    }

    public function store(Request $request): LinkResource
    {
        $validated = $request->validate([
            'short_slug' => 'required|alpha_dash|max:100|unique:links,short_slug',
            'target_url' => 'required|url|max:2048',
        ]);

        $link = Link::create($validated);

        return new LinkResource($link);
    }

    public function show(string $slug): LinkResource
    {
        $link = Link::where('short_slug', $slug)->firstOrFail();

        return new LinkResource($link);
    }

    public function update(Request $request, string $slug): LinkResource
    {
        $link = Link::where('short_slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'short_slug' => 'sometimes|alpha_dash|max:100|unique:links,short_slug,' . $link->id,
            'target_url' => 'sometimes|url|max:2048',
        ]);

        $link->update($validated);

        return new LinkResource($link);
    }

    public function destroy(string $slug): JsonResponse
    {
        $link = Link::where('short_slug', $slug)->firstOrFail();
        $link->clicks()->delete();
        $link->delete();

        return response()->json(['message' => 'Link deleted successfully']);
    }

    public function stats(string $slug): JsonResponse
    {
        $link = Link::where('short_slug', $slug)->firstOrFail();

        $stats = [
            'slug' => $link->short_slug,
            'target_url' => $link->target_url,
            'short_url' => $link->short_url,
            'total_clicks' => $link->clicks()->count(),
            'clicks_today' => $link->clicks()->whereDate('clicked_at', today())->count(),
            'clicks_this_week' => $link->clicks()->where('clicked_at', '>=', now()->subDays(7))->count(),
            'clicks_this_month' => $link->clicks()->where('clicked_at', '>=', now()->subDays(30))->count(),
            'recent_clicks' => $link->clicks()
                ->orderByDesc('clicked_at')
                ->limit(10)
                ->get()
                ->map(fn ($click) => [
                    'referrer' => $click->referrer,
                    'clicked_at' => $click->clicked_at->toIso8601String(),
                ]),
        ];

        return response()->json($stats);
    }
}
