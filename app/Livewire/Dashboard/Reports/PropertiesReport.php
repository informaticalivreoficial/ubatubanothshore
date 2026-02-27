<?php

namespace App\Livewire\Dashboard\Reports;

use App\Models\Property;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PropertiesReport extends Component
{
    public array $statusChart = [];
    public array $saleChart = [];
    public array $cityChart = [];
    public array $monthlyChart = [];
    public array $categoryChart = [];
    public array $typeChart = [];

    public function mount()
    {
        $this->loadStatusChart();
        $this->loadSaleChart();
        $this->loadCityChart();
        $this->loadMonthlyChart();
        $this->loadCategoryChart();
        $this->loadTypeChart();
    }

    /** 📊 Status */
    protected function loadStatusChart(): void
    {
        $data = Property::select(
                DB::raw("
                    CASE 
                        WHEN status = 1 THEN 'Ativo'
                        ELSE 'Inativo'
                    END as label
                "),
                DB::raw('count(*) as total')
            )
            ->groupBy('label')
            ->get();

        $this->statusChart = [
            'labels' => $data->pluck('label'),
            'data'   => $data->pluck('total'),
        ];
    }


    /** 🏷️ Venda x Locação */
    protected function loadSaleChart(): void
    {
        $data = Property::select(
                DB::raw("CASE WHEN sale = 1 THEN 'Venda' ELSE 'Locação' END as label"),
                DB::raw('count(*) as total')
            )
            ->groupBy('label')
            ->get();

        $this->saleChart = [
            'labels' => $data->pluck('label'),
            'data'   => $data->pluck('total'),
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
            'labels' => $data->pluck('city'),
            'data'   => $data->pluck('total'),
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
            'labels' => $data->pluck('month'),
            'data'   => $data->pluck('total'),
        ];
    }

    /** 🏷️ Imóveis por categoria */
    protected function loadCategoryChart(): void
    {
        $data = Property::select('category', DB::raw('count(*) as total'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $this->categoryChart = [
            'labels' => $data->pluck('category'),
            'data'   => $data->pluck('total'),
        ];
    }
    
    /** 🏠 Imóveis por tipo */
    protected function loadTypeChart(): void
    {
        $data = Property::select('type', DB::raw('count(*) as total'))
            ->whereNotNull('type')
            ->groupBy('type')
            ->orderByDesc('total')
            ->get();

        $this->typeChart = [
            'labels' => $data->pluck('type'),
            'data'   => $data->pluck('total'),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard.reports.properties-report')->with('title', 'Relatório de Imóveis');
    }
}
