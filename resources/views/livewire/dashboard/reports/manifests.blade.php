<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-chart-bar mr-2"></i> Relatórios de Manifestos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Relatórios de manifestos</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mt-5">
                <div class="col-lg-4">
                    <div x-data="pieChart(@js($chartCargoRepositionManifests))" class="w-full max-w-md mx-auto">
                        <canvas id="manifestsCargoRepositionPieChart" class="h-96"></canvas>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div x-data x-init="initStatusChart(@js($chartStatusData))">
                        <canvas id="statusChart" class="h-96"></canvas>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-6">
                    <div x-data="stackedBarChart(@js($chartMonthlyStacked))" class="w-full">
                        <canvas id="monthlyStackedChart" class="h-96"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function pieChart(data) {
        return {
            chart: null,
            init() {
                const ctx = document.getElementById('manifestsCargoRepositionPieChart').getContext('2d');
                this.chart = new Chart(ctx, {
                    type: 'pie',
                    data: data,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: true,
                                text: 'Manifestos de Carga & Reposição ({{ now()->year }})'
                            }
                        }
                    }
                });
            }
        };
    }

    function initStatusChart(data) {
        const ctx = document.getElementById('statusChart').getContext('2d');

        const allYears = [...new Set(Object.values(data).flatMap(statusData => Object.keys(statusData)))].sort();

        const colorPalette = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#6366f1', '#14b8a6'];

        const datasets = Object.entries(data).map(([status, values], index) => ({
            label: status,
            data: allYears.map(year => values[year] || 0),
            backgroundColor: colorPalette[index % colorPalette.length]
        }));

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: allYears,
                datasets: datasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Manifestos por Status e Ano'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    function stackedBarChart(data) {
        return {
            chart: null,
            init() {
                const ctx = document.getElementById('monthlyStackedChart').getContext('2d');
                this.chart = new Chart(ctx, {
                    type: 'bar',
                    data: data,
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Manifestos Mensais - Carga vs Reposição'
                            },
                            legend: {
                                position: 'top',
                            },
                        },
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        scales: {
                            x: {
                                stacked: true,
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }
    }
</script>
