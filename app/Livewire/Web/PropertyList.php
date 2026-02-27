<?php

namespace App\Livewire\Web;

use App\Models\Property;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class PropertyList extends Component
{
    use WithPagination;

    public $filters = [];
    public $perPage = 18;

    // ✅ parâmetros reutilizáveis
    public $highlighted = false;
    public $all = null;
    public $title = null;
    public $type = null;
    public $neighborhood = null;

    protected $listeners = ['filterUpdated' => 'applyFilters'];

    public function mount(
        $highlighted = false, 
        $all = null, 
        $title = null, 
        $type = null, 
        $neighborhood = null, 
        $filters = [])
    {
        $this->highlighted = $highlighted;
        $this->all = $all;
        $this->title = $title;
        $this->type = $type;
        $this->neighborhood = $neighborhood;
        $this->filters = $filters;
    }

    public function applyFilters($filters)
    {
        $this->filters = $filters;
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 18;
    }

    public function getFilteredProperties()
    {
        $query = Property::query();

        if ($this->highlighted) {
            $query->where('highlight', true);
        }

        if ($this->all) {
            $query->where(function ($q) {
                $q->where('sale', true)
                ->orWhere('location', true);
            });
        }

        if ($this->type === 'venda') {
            $query->where('sale', true);
        } elseif ($this->type === 'locacao') {
            $query->where('location', true);
        }

        if (!empty($this->neighborhood)) {
            $query->where('neighborhood', $this->neighborhood);
        }

        if (!empty($this->filters['operation'])) {
            if ($this->filters['operation'] === 'sale') {
                $query->where('sale', true);
            } elseif ($this->filters['operation'] === 'location') {
                $query->where('location', true);
            }
        }

        if (!empty($this->filters['cidade'])) {
            $query->where('city', $this->filters['cidade']);
        }

        if (!empty($this->filters['bairro'])) {
            $query->where('neighborhood', $this->filters['bairro']);
        }

        if (!empty($this->filters['valores'])) {
            if (($this->filters['operation'] ?? '') === 'sale') {
                $query->where('sale_value', '<=', $this->filters['valores']);
            } elseif (($this->filters['operation'] ?? '') === 'location') {
                $query->where('rental_value', '<=', $this->filters['valores']);
            } else {
                $query->where(function ($q) {
                    $q->where('sale_value', '<=', $this->filters['valores'])
                      ->orWhere('rental_value', '<=', $this->filters['valores']);
                });
            }
        }

        if (!empty($this->filters['dormitorios'])) {
            $query->where('dormitories', '>=', $this->filters['dormitorios']);
        }

        // 🔄 Ordenação mais recente primeiro
        //return $query->latest()->get();
        return $query->available()->latest()->paginate($this->perPage);        
    }

    public function render()
    {
        return view('livewire.web.property-list', [
            'properties' => $this->getFilteredProperties(),
        ]);
    }
}
