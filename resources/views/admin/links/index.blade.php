@extends('layouts.admin')

@section('title', 'All Links')

@section('content')
<div class="page-header">
    <h2 class="page-title">All Links</h2>
    <div class="page-actions">
        <a href="{{ route('admin.links.create') }}" class="btn btn--primary">New Link</a>
    </div>
</div>

<form method="GET" class="search-bar">
    <input
        type="text"
        name="q"
        class="form-input"
        placeholder="Search by slug or URL..."
        value="{{ request('q') }}"
    >
    <button type="submit" class="btn btn--ghost">Search</button>
    @if(request('q'))
        <a href="{{ route('admin.links.index') }}" class="btn btn--ghost">Clear</a>
    @endif
</form>

@if($links->isEmpty())
    <div class="empty-state">
        <div class="empty-state-title">No links found</div>
        @if(request('q'))
            <p class="empty-state-text">No links match your search criteria.</p>
            <a href="{{ route('admin.links.index') }}" class="btn btn--ghost">Clear search</a>
        @else
            <p class="empty-state-text">Create your first short link to get started.</p>
            <a href="{{ route('admin.links.create') }}" class="btn btn--primary">Create Link</a>
        @endif
    </div>
@else
    <p class="text-muted mb-m">
        <strong>{{ $links->total() }}</strong> link{{ $links->total() !== 1 ? 's' : '' }} found
    </p>

    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Short URL</th>
                    <th>Target URL</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($links as $link)
                <tr>
                    <td><code>{{ $link->short_slug }}</code></td>
                    <td class="truncate">{{ $link->target_url }}</td>
                    <td class="text-muted">{{ $link->created_at->format('M j, Y H:i') }}</td>
                    <td>
                        <div class="table-actions">
                            <a href="{{ route('admin.links.edit', $link) }}" class="btn btn--ghost btn--small">Edit</a>
                            <form
                                method="POST"
                                action="{{ route('admin.links.destroy', $link) }}"
                                onsubmit="return confirm('Delete this link? This will also delete all click data.')"
                                style="display: inline;"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn--danger btn--small">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($links->hasPages())
        <div class="pagination">
            @if($links->onFirstPage())
                <span class="pagination-link" style="opacity: 0.5;">&laquo;</span>
            @else
                <a href="{{ $links->previousPageUrl() }}" class="pagination-link">&laquo;</a>
            @endif

            @foreach($links->getUrlRange(1, $links->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="pagination-link {{ $page == $links->currentPage() ? 'active' : '' }}">
                    {{ $page }}
                </a>
            @endforeach

            @if($links->hasMorePages())
                <a href="{{ $links->nextPageUrl() }}" class="pagination-link">&raquo;</a>
            @else
                <span class="pagination-link" style="opacity: 0.5;">&raquo;</span>
            @endif
        </div>
    @endif
@endif
@endsection
