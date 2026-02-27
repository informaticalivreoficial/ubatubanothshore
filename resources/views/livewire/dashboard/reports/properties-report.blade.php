<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-chart-bar mr-2"></i> Relatórios de Imóveis</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Relatórios de imóveis</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>
    
    <div class="card">
        <div class="card-body">

            <div class="row mt-5">
                <div class="col-lg-6">
                    <h2 class="text-sm font-semibold mb-2">Imóveis por cidade</h2>
                    <canvas id="cityChart"></canvas>
                </div>
                <div class="col-lg-6">
                    <h2 class="text-sm font-semibold mb-2">Cadastros por mês</h2>
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <div class="row mt-5">                
                <div class="col-lg-6">
                    <h2 class="text-sm font-semibold mb-2">Imóveis por tipo</h2>
                    <canvas id="typeChart"></canvas>
                </div>
                <div class="col-lg-6">
                    <h2 class="text-sm font-semibold mb-2">Imóveis por categoria</h2>
                    <div x-data="pieChart(@js($categoryChart))" class="w-full max-w-md mx-auto">
                        <canvas id="categoryChart" class="h-96"></canvas>
                    </div> 
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-lg-6">
                    <h2 class="text-sm font-semibold mb-2">📊 Relatório de Imóveis</h2>
                    <div x-data="pieChart(@js($statusChart))" class="w-full max-w-md mx-auto">
                        <canvas id="statusChart" class="h-96"></canvas>
                    </div>                    
                </div>
                <div class="col-lg-6">
                   <h2 class="text-sm font-semibold mb-2">Venda x Locação</h2>
                   <div x-data="doughnutChart(@js($saleChart))" class="w-full max-w-md mx-auto">
                        <canvas id="saleChart" class="h-96"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
        document.addEventListener('livewire:init', () => {

            new Chart(document.getElementById('statusChart'), {
                type: 'pie',
                data: {
                    labels: @json($statusChart['labels']),
                    datasets: [{
                        data: @json($statusChart['data']),
                        backgroundColor: ['#22c55e','#facc15','#ef4444','#3b82f6']
                    }]
                }
            });

            new Chart(document.getElementById('saleChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($saleChart['labels']),
                    datasets: [{
                        data: @json($saleChart['data']),
                        backgroundColor: ['#3b82f6','#22c55e']
                    }]
                }
            });

            new Chart(document.getElementById('cityChart'), {
                type: 'bar',
                data: {
                    labels: @json($cityChart['labels']),
                    datasets: [{
                        label: 'Imóveis',
                        data: @json($cityChart['data']),
                        backgroundColor: '#6366f1'
                    }]
                }
            });

            new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: {
                    labels: @json($monthlyChart['labels']),
                    datasets: [{
                        label: 'Imóveis cadastrados',
                        data: @json($monthlyChart['data']),
                        borderColor: '#22c55e',
                        fill: false
                    }]
                }
            });

            new Chart(document.getElementById('categoryChart'), {
                type: 'pie',
                data: {
                    labels: @json($categoryChart['labels']),
                    datasets: [{
                        data: @json($categoryChart['data']),
                        backgroundColor: [
                            '#22c55e', '#3b82f6', '#facc15',
                            '#ef4444', '#8b5cf6', '#14b8a6'
                        ],
                    }]
                }
            });

            new Chart(document.getElementById('typeChart'), {
                type: 'bar',
                data: {
                    labels: @json($typeChart['labels']),
                    datasets: [{
                        label: 'Quantidade',
                        data: @json($typeChart['data']),
                        backgroundColor: '#6366f1'
                    }]
                }
            });
            

        });
    </script>