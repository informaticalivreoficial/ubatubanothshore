<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-chart-bar mr-2"></i> Relatórios de Empresas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Relatórios de empresas</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <div x-data="pieChart(@js($chartActiveInactiveCompanies))" class="w-full max-w-md mx-auto">
                        <canvas id="companyStatusPieChart" class="h-96"></canvas>
                    </div>
                </div>
                <div class="col-lg-6">
                   
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
                const ctx = document.getElementById('companyStatusPieChart').getContext('2d');
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
                                text: 'Empresas Ativas & Inativas'
                            }
                        }
                    }
                });
            }
        };
    }
</script>
