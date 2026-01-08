@extends('layouts.admin')

@section('title', 'Redirect Logs')

@section('content')
<div class="page-header">
    <h2 class="page-title">Recent Redirect Logs</h2>
</div>

@if($clicks->isEmpty())
    <div class="empty-state">
        <div class="empty-state-title">No redirect logs yet</div>
        <p class="empty-state-text">Redirect logs will appear here once your links start getting traffic.</p>
    </div>
@else
    <p class="text-muted mb-m">Showing last 50 redirects</p>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Slug</th>
                    <th>Referrer</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clicks as $click)
                <tr>
                    <td class="text-muted">{{ $click->clicked_at->format('M j, Y H:i:s') }}</td>
                    <td><code>{{ $click->slug }}</code></td>
                    <td class="truncate text-muted">{{ $click->referrer ?: '-' }}</td>
                    <td class="text-muted">{{ $click->ip_address ?: '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

<p class="mt-m">
    <a href="{{ route('admin.dashboard') }}">&larr; Back to Dashboard</a>
</p>
@endsection
