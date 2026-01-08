@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h2 class="page-title">Dashboard</h2>
    <div class="page-actions">
        <a href="{{ route('admin.links.create') }}" class="btn btn--primary">New Link</a>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-value">{{ number_format($totalLinks) }}</div>
        <div class="stat-label">Total Links</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ number_format($totalClicks) }}</div>
        <div class="stat-label">Total Clicks</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Links</h3>
    </div>

    @if($recentLinks->isEmpty())
        <div class="empty-state">
            <div class="empty-state-title">No links yet</div>
            <p class="empty-state-text">Create your first short link to get started.</p>
            <a href="{{ route('admin.links.create') }}" class="btn btn--primary">Create Link</a>
        </div>
    @else
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Short URL</th>
                        <th>Target URL</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLinks as $link)
                    <tr>
                        <td><code>{{ $link->short_slug }}</code></td>
                        <td class="truncate">{{ $link->target_url }}</td>
                        <td class="text-muted">{{ $link->created_at->format('M j, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.links.edit', $link) }}" class="btn btn--ghost btn--small">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="mt-m">
            <a href="{{ route('admin.links.index') }}">View all links &rarr;</a>
        </p>
    @endif
</div>
@endsection
