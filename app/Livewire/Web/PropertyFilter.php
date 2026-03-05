<?php

namespace App\Livewire\Web;

use App\Models\Property;
use Livewire\Component;

class PropertyFilter extends Component
{
    public $activeNeighborhood = null;
    public $perPage = 12; 

    public function setNeighborhood($neighborhood = null)
    {
        $this->activeNeighborhood = $neighborhood;
        $this->reset('perPage');
        $this->perPage = 12;
    }

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function getPropertiesProperty()
    {
        return Property::query()
            ->when($this->activeNeighborhood, function ($query) {
                $query->where('neighborhood', $this->activeNeighborhood);
            })
            ->available()
            ->latest()
            ->take($this->perPage)
            ->get();
    }

    public function getNeighborhoodsProperty()
    {
        return Property::query()
            ->selectRaw('neighborhood, COUNT(*) as total')
            ->groupBy('neighborhood')
            ->orderBy('neighborhood')
            ->get();
    }

    public function getTotalGeralProperty()
    {
        return Property::available()->count();
    }

    public function getTotalProperty()
    {
        return Property::query()
            ->when($this->activeNeighborhood, function ($query) {
                $query->where('neighborhood', $this->activeNeighborhood);
            })
            ->count();
    }

    public function render()
    {
        return view('livewire.web.property-filter');
    }   
}
