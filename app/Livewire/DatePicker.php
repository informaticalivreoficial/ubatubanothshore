<?php

namespace App\Livewire;

use Livewire\Component;

class DatePicker extends Component
{
    public $dataSelecionada;

    protected $rules = [
        'dataSelecionada' => 'required|date_format:d/m/Y|before:today', 
    ];

    public function updatedDataSelecionada($value)
    {
        $this->validateOnly('dataSelecionada');
        $this->dispatch('atualizar-data', $value);
    }
    
    public function render()
    {
        return view('livewire.date-picker');
    }
}
