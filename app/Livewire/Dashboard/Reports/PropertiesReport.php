<?php

namespace App\Livewire\Dashboard\Reports;

use App\Models\Property;
use App\Models\PropertyReservation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PropertiesReport extends Component
{
    // Imóveis
    public array $statusChart   = [];
    public array $cityChart     = [];
    public array $monthlyChart  = [];
    public array $categoryChart = [];
    public array $typeChart     = [];

    // Reservas
    public array $reservasMensalChart     = [];
    public array $receitaMensalChart      = [];
    public array $paymentStatusChart      = [];
    public array $origemReservasChart     = [];

    // KPIs
    public int    $totalImoveis      = 0;
    public int    $totalAtivos       = 0;
    public int    $totalReservas     = 0;
    public string $receitaTotal      = 'R$ 0,00';
    public string $ticketMedio       = 'R$ 0,00';

    public function mount(): void
    {
        $this->loadKpis();

        // Imóveis
        $this->loadStatusChart();
        $this->loadCityChart();
        $this->loadMonthlyChart();
        $this->loadCategoryChart();
        $this->loadTypeChart();

        // Reservas
        $this->loadReservasMensalChart();
        $this->loadReceitaMensalChart();
        $this->loadPaymentStatusChart();
        $this->loadOrigemReservasChart();
    }

    // =========================
    // KPIs
    // =========================

    protected function loadKpis(): void
    {
        $this->totalImoveis = Property::count();
        $this->totalAtivos  = Property::where('status', 1)->count();

        $reservas = PropertyReservation::selectRaw('count(*) as total, sum(total_value) as receita')
            ->first();

        $this->totalReservas = (int) ($reservas->total ?? 0);

        $receita = (float) ($reservas->receita ?? 0);
        $this->receitaTotal = 'R$ ' . number_format($receita, 2, ',', '.');
        $this->ticketMedio  = $this->totalReservas > 0
            ? 'R$ ' . number_format($receita / $this->totalReservas, 2, ',', '.')
            : 'R$ 0,00';
    }

    // =========================
    // IMÓVEIS
    // =========================

    /** 📊 Status */
    protected function loadStatusChart(): void
    {
        $data = Property::select(
                DB::raw("CASE WHEN status = 1 THEN 'Ativo' ELSE 'Inativo' END as label"),
                DB::raw('count(*) as total')
            )
            ->groupBy('label')
            ->get();

        $this->statusChart = [
            'labels' => $data->pluck('label')->values(),
            'data'   => $data->pluck('total')->values(),
        ];
    }

    /** 📍 Por cidade */
    protected function loadCityChart(): void
    {
        $data = Property::select('city', DB::raw('count(*) as total'))
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $this->cityChart = [
            'labels' => $data->pluck('city')->values(),
            'data'   => $data->pluck('total')->values(),
        ];
    }

    /** 📅 Cadastros por mês */
    protected function loadMonthlyChart(): void
    {
        $data = Property::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $this->monthlyChart = [
            'labels' => $data->pluck('month')->values(),
            'data'   => $data->pluck('total')->values(),
        ];
    }

    /** 🏷️ Por categoria */
    protected function loadCategoryChart(): void
    {
        $data = Property::select('category', DB::raw('count(*) as total'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $this->categoryChart = [
            'labels' => $data->pluck('category')->values(),
            'data'   => $data->pluck('total')->values(),
        ];
    }

    /** 🏠 Por tipo */
    protected function loadTypeChart(): void
    {
        $data = Property::select('type', DB::raw('count(*) as total'))
            ->whereNotNull('type')
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();

        $this->typeChart = [
            'labels' => $data->pluck('type')->values(),
            'data'   => $data->pluck('total')->values(),
        ];
    }

    // =========================
    // RESERVAS
    // =========================

    /** 📅 Reservas por mês */
    protected function loadReservasMensalChart(): void
    {
        $data = PropertyReservation::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $this->reservasMensalChart = [
            'labels' => $data->pluck('month')->values(),
            'data'   => $data->pluck('total')->values(),
        ];
    }

    /** 💰 Receita mensal (total_value) */
    protected function loadReceitaMensalChart(): void
    {
        $data = PropertyReservation::select(
                DB::raw("DATE_FORMAT(check_in, '%Y-%m') as month"),
                DB::raw('sum(total_value) as total')
            )
            ->whereNotNull('total_value')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $this->receitaMensalChart = [
            'labels' => $data->pluck('month')->values(),
            'data'   => $data->pluck('total')->map(fn ($v) => round((float) $v, 2))->values(),
        ];
    }

    /** 💳 Status de pagamento */
    protected function loadPaymentStatusChart(): void
    {
        $data = PropertyReservation::select('payment_status', DB::raw('count(*) as total'))
            ->whereNotNull('payment_status')
            ->groupBy('payment_status')
            ->orderByDesc('total')
            ->get();

        $this->paymentStatusChart = [
            'labels' => $data->pluck('payment_status')->values(),
            'data'   => $data->pluck('total')->values(),
        ];
    }

    /** 🌐 Origem das reservas */
    protected function loadOrigemReservasChart(): void
    {
        $data = PropertyReservation::select('origin', DB::raw('count(*) as total'))
            ->whereNotNull('origin')
            ->groupBy('origin')
            ->orderByDesc('total')
            ->get();

        $this->origemReservasChart = [
            'labels' => $data->pluck('origin')->values(),
            'data'   => $data->pluck('total')->values(),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.reports.properties-report')
            ->with('title', 'Relatório de Imóveis e Reservas');
    }
}