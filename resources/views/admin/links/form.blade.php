@extends('layouts.admin')

@section('title', isset($link) ? 'Edit Link' : 'Create Link')

@section('content')
<div class="page-header">
    <h2 class="page-title">{{ isset($link) ? 'Edit Link' : 'Create New Link' }}</h2>
</div>

<div class="card" style="max-width: 40rem;">
    <form method="POST" action="{{ isset($link) ? route('admin.links.update', $link) : route('admin.links.store') }}">
        @csrf
        @if(isset($link))
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="short_slug" class="form-label">Short Slug</label>
            <input
                type="text"
                id="short_slug"
                name="short_slug"
                class="form-input"
                value="{{ old('short_slug', $link->short_slug ?? '') }}"
                placeholder="e.g., spotify, newsletter, twitter"
                required
            >
            <p class="form-help">
                Letters, numbers, dashes and underscores only. This becomes: {{ config('app.url') }}/<strong>your-slug</strong>
            </p>
            @error('short_slug')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="target_url" class="form-label">Target URL</label>
            <input
                type="url"
                id="target_url"
                name="target_url"
                class="form-input"
                value="{{ old('target_url', $link->target_url ?? '') }}"
                placeholder="https://example.com/your-long-url"
                required
            >
            <p class="form-help">
                The full URL where this short link will redirect to.
            </p>
            @error('target_url')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <div style="display: flex; gap: var(--space-xs); flex-wrap: wrap;">
            <button type="submit" class="btn btn--primary">
                {{ isset($link) ? 'Update Link' : 'Create Link' }}
            </button>
            <a href="{{ route('admin.links.index') }}" class="btn btn--ghost">Cancel</a>
        </div>
    </form>
</div>
@endsection
