<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RedirectController extends Controller
{
    public function redirect(string $slug, Request $request): RedirectResponse|Response
    {
        $link = Link::where('short_slug', $slug)->first();

        if (!$link) {
            abort(404, "Sorry, that link doesn't exist.");
        }

        Click::create([
            'slug' => $slug,
            'referrer' => $request->header('referer'),
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
        ]);

        return redirect($link->target_url, 302);
    }
}
