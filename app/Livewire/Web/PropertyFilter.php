<?php

namespace App\Livewire\Web;

use App\Models\Property;
use Livewire\Component;

class PropertyFilter extends Component
{
    public $operation = ''; // 'sale' ou 'location'
    public $cidade = '';
    public $bairro = '';
    public $valores = '';
    public $dormitorios = '';

    public $valoresOptions = [];
    public $bairros = [];

    public function mount()
    {
        $this->updateValoresOptions();
    }

    public function updatedOperation()
    {
        $this->valores = ''; // limpa o valor anterior
        $this->updateValoresOptions();
        $this->emitFilter();
    }

    public function updatedCidade($value)
    {
        // Filtra bairros da cidade selecionada
        $this->bairros = Property::where('city', $value)
            ->select('neighborhood')
            ->distinct()
            ->orderBy('neighborhood')
            ->pluck('neighborhood')
            ->toArray();

        $this->bairro = ''; // limpa bairro anterior
        $this->emitFilter();
    }

    public function render()
    {
        $cidades = Property::select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->toArray();

        return view('livewire.web.property-filter', [
            'cidades' => $cidades,
            'bairros' => $this->bairros
        ]);
    }

    public function updated($field)
    {
        if (!in_array($field, ['operation', 'cidade'])) {
            $this->emitFilter();
        }
    }

    protected function emitFilter()
    {
        $this->dispatch('filterUpdated', [
            'operation' => $this->operation,
            'cidade' => $this->cidade,
            'bairro' => $this->bairro,
            'valores' => $this->valores,
            'dormitorios' => $this->dormitorios,
        ]);
    }

    protected function updateValoresOptions()
    {
        if ($this->operation === 'sale') {
            $this->valoresOptions = [
                '300000' => 'R$ 300.000',
                '600000' => 'R$ 600.000',
                '900000' => 'R$ 900.000',
                '1500000' => 'R$ 1.500.000',
                '2500000' => 'R$ 2.500.000',
                '5000000' => 'R$ 5.000.000',
                '10000000' => 'R$ 10.000.000',
            ];
        } elseif ($this->operation === 'location') {
            $this->valoresOptions = [
                '1000' => 'R$ 1.000',
                '2000' => 'R$ 2.000',
                '3000' => 'R$ 3.000',
                '5000' => 'R$ 5.000',
                '8000' => 'R$ 8.000',
                '10000' => 'R$ 10.000',
            ];
        } else {
            $this->valoresOptions = [];
        }
    }
}
