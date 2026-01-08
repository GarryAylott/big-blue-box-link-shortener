<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LinkController extends Controller
{
    public function index(Request $request): View
    {
        $query = Link::query();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('short_slug', 'like', "%{$search}%")
                  ->orWhere('target_url', 'like', "%{$search}%");
            });
        }

        $links = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('admin.links.index', compact('links'));
    }

    public function create(): View
    {
        return view('admin.links.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'short_slug' => 'required|alpha_dash|max:100|unique:links,short_slug',
            'target_url' => 'required|url|max:2048',
        ]);

        Link::create($validated);

        return redirect()->route('admin.links.index')
            ->with('success', 'Link created successfully.');
    }

    public function edit(Link $link): View
    {
        return view('admin.links.form', compact('link'));
    }

    public function update(Request $request, Link $link): RedirectResponse
    {
        $validated = $request->validate([
            'short_slug' => 'required|alpha_dash|max:100|unique:links,short_slug,' . $link->id,
            'target_url' => 'required|url|max:2048',
        ]);

        $link->update($validated);

        return redirect()->route('admin.links.index')
            ->with('success', 'Link updated successfully.');
    }

    public function destroy(Link $link): RedirectResponse
    {
        $link->clicks()->delete();
        $link->delete();

        return redirect()->route('admin.links.index')
            ->with('success', 'Link deleted.');
    }
}
