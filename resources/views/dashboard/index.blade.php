@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold display-5 text-primary">
        Sales Dashboard 
    </h1>

    <!-- Summary Cards -->
    <div class="row mb-5 g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-lg rounded-4 bg-white">
                <div class="card-body text-center p-4">
                    <i class="bi bi-cash-coin fs-1 text-info mb-3"></i>
                    <h6 class="text-muted">Total Sales</h6>
                    <h2 class="fw-bold text-info">₱{{ number_format($totalSales, 2) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-lg rounded-4 bg-white">
                <div class="card-body text-center p-4">
                    <i class="bi bi-bag-check fs-1 text-success mb-3"></i>
                    <h6 class="text-muted">Total Number of Sales</h6>
                    <h2 class="fw-bold text-success">{{ $salesCount }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-lg rounded-4 bg-white">
                <div class="card-body p-4">
                    <h6 class="text-muted text-center mb-3">Sales Per Region</h6>
                    <ul class="list-group list-group-flush">
                        @foreach ($salesPerRegion as $region)
                            <li class="list-group-item d-flex justify-content-between bg-light">
                                <span>{{ $region['region_name'] }}</span>
                                <span class="fw-semibold text-primary">{{ number_format($region['total_units']) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-white border-bottom-0 text-center fw-bold fs-5 py-3 text-info">
                    Sales Count by Region
                </div>
                <div class="card-body">
                    <canvas id="salesPerRegionChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-white border-bottom-0 text-center fw-bold fs-5 py-3 text-success">
                    Total Sales Per Date
                </div>
                <div class="card-body">
                    <canvas id="salesPerMonthChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Sales Per Region Chart
new Chart(document.getElementById('salesPerRegionChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($salesPerRegion->pluck('region_name')) !!},
        datasets: [{
            label: 'Units Sold',
            data: {!! json_encode($salesPerRegion->pluck('total_units')) !!},
            backgroundColor: 'rgba(240, 13, 13, 0.6)', // Info color
            borderColor: 'rgb(28, 40, 82)',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});

// Sales Per Month Chart
new Chart(document.getElementById('salesPerMonthChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($salesPerMonth->map(fn($item) => \Carbon\Carbon::parse($item->month)->format('d-m-Y'))) !!},
        datasets: [{
            label: 'Units Sold',
            data: {!! json_encode($salesPerMonth->pluck('total_units')) !!},
            backgroundColor: 'rgba(26, 49, 148, 0.1)', // Success fill
            borderColor: 'rgb(0, 255, 255)',
            pointBackgroundColor: 'rgb(21, 111, 122)',
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            x: {
                ticks: {
                    autoSkip: true,
                    maxRotation: 45,
                    minRotation: 45,
                }
            },
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
