<?php

namespace App\Livewire\Dashboard\Billing;

use App\Models\TableOfValue;
use Livewire\Component;

class TableOfValues extends Component
{
    public $id = 1;

    public float $dry_weight = 0.0;
    public float $horti_fruit = 0.0;
    public float $glace = 0.0;
    public float $general_1000_5000 = 0.0;
    public float $general_above_5000 = 0.0;
    public float $cubage = 0.0;
    public float $tax = 0.0;
    public float $package = 0.0;
    public float $secure = 0.0;

    public function render()
    {
        $title = 'Tabela de valores para frete';
        return view('livewire.dashboard.billing.table-of-values')->with('title', $title);
    }

    public function mount()
    {
        $values = TableOfValue::findOrFail($this->id);
        
        $this->dry_weight = $values->dry_weight;
        $this->horti_fruit = $values->horti_fruit;
        $this->glace = $values->glace;
        $this->general_1000_5000 = $values->general_1000_5000;
        $this->general_above_5000 = $values->general_above_5000;
        $this->cubage = $values->cubage;
        $this->tax = $values->tax;
        $this->package = $values->package;
        $this->secure = $values->secure;
    }

    public function update()
    {
        $this->validate([
            'dry_weight' => 'required|numeric|min:0',
            'horti_fruit' => 'required|numeric|min:0',
            'glace' => 'required|numeric|min:0',
            'general_1000_5000' => 'required|numeric|min:0',
            'general_above_5000' => 'required|numeric|min:0',
            'cubage' => 'required|numeric|min:0',
            'tax' => 'required|numeric|min:0',
            'package' => 'required|numeric|min:0',
            'secure' => 'required|numeric|min:0',
        ]);

        $values = TableOfValue::findOrFail($this->id);

        //dd($this->dry_weight);
        $values->update([
            'dry_weight' => $this->dry_weight,
            'horti_fruit' => $this->horti_fruit,
            'glace' => $this->glace,
            'general_1000_5000' => $this->general_1000_5000,
            'general_above_5000' => $this->general_above_5000,
            'cubage' => $this->cubage,
            'tax' => $this->tax,
            'package' => $this->package,
            'secure' => $this->secure,
        ]);

        $this->dispatch(['atualizado']);

        //session()->flash('success', 'Tabela de valores atualizada com sucesso!');
    }
}
