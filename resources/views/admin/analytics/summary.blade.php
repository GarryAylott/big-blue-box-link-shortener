@extends('layouts.admin')

@section('title', 'Analytics')

@section('content')
<div class="page-header">
    <h2 class="page-title">Analytics</h2>
</div>

<div class="chart-container">
    <h3 class="chart-title">Clicks - Last 7 Days</h3>
    <canvas id="clicksChart" height="100"></canvas>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Total Clicks by Link</h3>
    </div>

    @if($totals->isEmpty())
        <div class="empty-state">
            <div class="empty-state-title">No click data yet</div>
            <p class="empty-state-text">Click data will appear here once your links start getting traffic.</p>
        </div>
    @else
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Slug</th>
                        <th class="text-right">Total Clicks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($totals as $row)
                    <tr>
                        <td><code>{{ $row->slug }}</code></td>
                        <td class="text-right">{{ number_format($row->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<p class="mt-m">
    <a href="{{ route('admin.dashboard') }}">&larr; Back to Dashboard</a>
</p>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('clicksChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Clicks',
                data: @json($data),
                borderColor: 'hsl(215, 95%, 55%)',
                backgroundColor: 'hsla(215, 95%, 55%, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: 'hsl(215, 95%, 55%)',
                pointBorderColor: 'hsl(215, 95%, 55%)',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: 'hsl(219, 11%, 70%)',
                        stepSize: 1
                    },
                    grid: {
                        color: 'hsl(218, 15%, 20%)'
                    }
                },
                x: {
                    ticks: {
                        color: 'hsl(219, 11%, 70%)'
                    },
                    grid: {
                        color: 'hsl(218, 15%, 20%)'
                    }
                }
            }
        }
    });
</script>
@endpush
