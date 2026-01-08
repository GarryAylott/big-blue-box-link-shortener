<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\Click;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalLinks = Link::count();
        $totalClicks = Click::count();
        $recentLinks = Link::orderByDesc('created_at')->limit(5)->get();

        return view('admin.dashboard', compact('totalLinks', 'totalClicks', 'recentLinks'));
    }
}
