<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Click;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function summary(): View
    {
        $totals = Click::select('slug', DB::raw('COUNT(*) as total'))
            ->groupBy('slug')
            ->orderByDesc('total')
            ->get();

        $daily = Click::select(
                DB::raw('DATE(clicked_at) as click_date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('clicked_at', '>=', now()->subDays(7))
            ->groupBy('click_date')
            ->orderBy('click_date')
            ->get();

        $labels = $daily->pluck('click_date');
        $data = $daily->pluck('count');

        return view('admin.analytics.summary', compact('totals', 'labels', 'data'));
    }

    public function clicks(): View
    {
        $clicks = Click::orderByDesc('clicked_at')->limit(50)->get();

        return view('admin.analytics.clicks', compact('clicks'));
    }
}
