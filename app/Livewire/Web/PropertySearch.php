<?php

namespace App\Livewire\Web;

use App\Models\Property;
use Livewire\Component;

class PropertySearch extends Component
{
    public $check_in;
    public $check_out;
    public $guests = 1;
    public $perPage = 12;

    public $properties; // resultados da busca

    public function mount()
    {
        $this->search(); // carregar resultados iniciais
    }

    public function updated($field)
    {
        // Resetar perPage quando mudar algum filtro
        if (in_array($field, ['check_in', 'check_out', 'guests'])) {
            $this->perPage = 12;
        }

        $this->search();
    }

    public function incrementGuests()
    {
        $this->guests++;
        $this->perPage = 12;
        $this->search();
    }

    public function decrementGuests()
    {
        if ($this->guests > 1) {
            $this->guests--;
            $this->perPage = 12;
            $this->search();
        }
    }

    public function loadMore()
    {
        $this->perPage += 12;
        $this->search();
    }

    public function search()
    {
        $query = Property::available();

        // Filtra por datas disponíveis
        if ($this->check_in && $this->check_out) {
            $query->whereDoesntHave('reservations', function ($q) {
                $q->where(function ($q2) {
                    $q2->whereBetween('start_date', [$this->check_in, $this->check_out])
                       ->orWhereBetween('end_date', [$this->check_in, $this->check_out])
                       ->orWhere(function ($q3) {
                           $q3->where('start_date', '<=', $this->check_in)
                              ->where('end_date', '>=', $this->check_out);
                       });
                });
            });

            $query->whereDoesntHave('blockedDates', function ($q) {
                $q->whereBetween('date', [$this->check_in, $this->check_out]);
            });
        }

        // Filtra por capacidade
        if ($this->guests) {
            $query->where('capacity', '>=', $this->guests);
        }

        // Carrega avaliações aprovadas
        $this->properties = $query
            ->withCount(['reviews as reviews_count' => fn($q) => $q->approved()])
            ->withAvg(['reviews as reviews_avg_rating' => fn($q) => $q->approved()], 'rating')
            ->latest()
            ->take($this->perPage)
            ->get();
    }

    public function render()
    {
        return view('livewire.web.property-search');
    }
}
