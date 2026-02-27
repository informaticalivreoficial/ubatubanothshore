<?php

namespace App\Livewire\Dashboard\Properties;

use App\Models\Property;
use Livewire\Component;

class ReservationCalendar extends Component
{
    public Property $property;

    public $checkin;
    public $checkout;
    public $total = 0;
    public $available = null;

    public function mount(Property $property)
    {
        $this->property = $property;
    }

    public function updated($field)
    {
        if ($this->checkin && $this->checkout) {

            $this->available = $this->property
                ->isAvailable($this->checkin, $this->checkout);

            if ($this->available) {
                $this->total = $this->property
                    ->calculatePrice($this->checkin, $this->checkout);
            } else {
                $this->total = 0;
            }
        }
    }
    public function render()
    {
        return view('livewire.dashboard.properties.reservation-calendar');
    }
}
