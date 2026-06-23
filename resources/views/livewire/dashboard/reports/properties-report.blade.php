<div>
    @section('title', $title)

    {{-- HEADER --}}
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-chart-bar mr-2"></i> Relatórios de Imóveis e Reservas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Relatórios</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- KPIs --}}
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalImoveis }}</h3>
                    <p>Total de Imóveis</p>
                </div>
                <div class="icon"><i class="fas fa-home"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $totalAtivos }}</h3>
                    <p>Imóveis Ativos</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $totalReservas }}</h3>
                    <p>Total de Reservas</p>
                </div>
                <div class="icon"><i class="fas fa-calendar-check"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 mb-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3 class="text-xl">{{ $receitaTotal }}</h3>
                    <p>Receita Total</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
        </div>
    </div>

    {{-- SEÇÃO: IMÓVEIS --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-home mr-2"></i> Imóveis</h3>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-lg-6 mb-5">
                    <h6 class="text-sm font-semibold mb-2 text-muted text-uppercase">Imóveis por cidade (Top 10)</h6>
                    <canvas id="cityChart"></canvas>
                </div>
                <div class="col-lg-6 mb-5">
                    <h6 class="text-sm font-semibold mb-2 text-muted text-uppercase">Cadastros por mês</h6>
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 mb-5">
                    <h6 class="text-sm font-semibold mb-2 text-muted text-uppercase">Status dos imóveis</h6>
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="col-lg-4 mb-5">
                    <h6 class="text-sm font-semibold mb-2 text-muted text-uppercase">Por categoria</h6>
                    <canvas id="categoryChart"></canvas>
                </div>
                <div class="col-lg-4 mb-5">
                    <h6 class="text-sm font-semibold mb-2 text-muted text-uppercase">Por tipo</h6>
                    <canvas id="typeChart"></canvas>
                </div>
            </div>

        </div>
    </div>

    {{-- SEÇÃO: RESERVAS --}}
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i> Reservas</h3>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-lg-6 mb-5">
                    <h6 class="text-sm font-semibold mb-2 text-muted text-uppercase">Reservas por mês</h6>
                    <canvas id="reservasMensalChart"></canvas>
                </div>
                <div class="col-lg-6 mb-5">
                    <h6 class="text-sm font-semibold mb-2 text-muted text-uppercase">Receita mensal (R$)</h6>
                    <canvas id="receitaMensalChart"></canvas>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-5">
                    <h6 class="text-sm font-semibold mb-2 text-muted text-uppercase">Status de pagamento</h6>
                    <canvas id="paymentStatusChart"></canvas>
                </div>
                <div class="col-lg-6 mb-5">
                    <h6 class="text-sm font-semibold mb-2 text-muted text-uppercase">Origem das reservas</h6>
                    <canvas id="origemReservasChart"></canvas>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 offset-lg-4 text-center">
                    <div class="p-4 border rounded">
                        <p class="text-muted mb-1">Ticket médio por reserva</p>
                        <h3 class="text-success font-weight-bold">{{ $ticketMedio }}</h3>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {

        const COLORS = [
            '#6366f1','#22c55e','#facc15','#ef4444',
            '#3b82f6','#14b8a6','#f97316','#8b5cf6',
            '#ec4899','#06b6d4'
        ];

        const defaultOptions = {
            responsive: true,
            plugins: { legend: { position: 'bottom' } }
        };

        // --- IMÓVEIS ---

        new Chart(document.getElementById('cityChart'), {
            type: 'bar',
            data: {
                labels: @json($cityChart['labels']),
                datasets: [{
                    label: 'Imóveis',
                    data: @json($cityChart['data']),
                    backgroundColor: '#6366f1'
                }]
            },
            options: { ...defaultOptions, plugins: { legend: { display: false } } }
        });

        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: @json($monthlyChart['labels']),
                datasets: [{
                    label: 'Imóveis cadastrados',
                    data: @json($monthlyChart['data']),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99,102,241,0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: defaultOptions
        });

        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: @json($statusChart['labels']),
                datasets: [{
                    data: @json($statusChart['data']),
                    backgroundColor: ['#22c55e','#ef4444']
                }]
            },
            options: defaultOptions
        });

        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: @json($categoryChart['labels']),
                datasets: [{
                    data: @json($categoryChart['data']),
                    backgroundColor: COLORS
                }]
            },
            options: defaultOptions
        });

        new Chart(document.getElementById('typeChart'), {
            type: 'doughnut',
            data: {
                labels: @json($typeChart['labels']),
                datasets: [{
                    data: @json($typeChart['data']),
                    backgroundColor: COLORS
                }]
            },
            options: defaultOptions
        });

        // --- RESERVAS ---

        new Chart(document.getElementById('reservasMensalChart'), {
            type: 'bar',
            data: {
                labels: @json($reservasMensalChart['labels']),
                datasets: [{
                    label: 'Reservas',
                    data: @json($reservasMensalChart['data']),
                    backgroundColor: '#14b8a6'
                }]
            },
            options: { ...defaultOptions, plugins: { legend: { display: false } } }
        });

        new Chart(document.getElementById('receitaMensalChart'), {
            type: 'line',
            data: {
                labels: @json($receitaMensalChart['labels']),
                datasets: [{
                    label: 'Receita (R$)',
                    data: @json($receitaMensalChart['data']),
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34,197,94,0.1)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => 'R$ ' + ctx.parsed.y.toLocaleString('pt-BR', { minimumFractionDigits: 2 })
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('paymentStatusChart'), {
            type: 'pie',
            data: {
                labels: @json($paymentStatusChart['labels']),
                datasets: [{
                    data: @json($paymentStatusChart['data']),
                    backgroundColor: ['#22c55e','#facc15','#ef4444','#3b82f6','#8b5cf6']
                }]
            },
            options: defaultOptions
        });

        new Chart(document.getElementById('origemReservasChart'), {
            type: 'doughnut',
            data: {
                labels: @json($origemReservasChart['labels']),
                datasets: [{
                    data: @json($origemReservasChart['data']),
                    backgroundColor: COLORS
                }]
            },
            options: defaultOptions
        });

    });
</script>