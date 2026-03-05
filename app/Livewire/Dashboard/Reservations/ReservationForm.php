<?php

namespace App\Livewire\Dashboard\Reservations;

use App\Models\Property;
use App\Models\PropertyReservation;
use Carbon\Carbon;
use Livewire\Component;

class ReservationForm extends Component
{
    public ?PropertyReservation $reservation = null;

    public $property_id;
    public $guest_name;
    public $guest_email;
    public $guest_phone;

    public $check_in;
    public $check_out;
    public $guests = 1;
    public $nights;

    public $daily_total;
    public $cleaning_fee = 0;
    public $total_value;

    public $status = 'pending';
    public $notes;

    public $blockedDates = [];

    public function mount(PropertyReservation $reservation = null)
    {
        if($reservation){
            $this->reservation = $reservation;
            $this->check_in = $reservation->check_in->format('d/m/Y');
            $this->check_out = $reservation->check_out->format('d/m/Y');
            $this->fill($reservation->toArray());
        }        
    }

    public function loadBlockedDates()
    {
        if (!$this->property_id) return;

        $reservations = PropertyReservation::where('property_id', $this->property_id)
            ->when($this->reservation?->id, fn ($q) =>
                $q->where('id', '!=', $this->reservation->id)
            )
            ->whereIn('status', ['pending','confirmed'])
            ->get();

        $dates = [];

        foreach ($reservations as $reservation) {

            $period = \Carbon\CarbonPeriod::create(
                $reservation->check_in,
                $reservation->check_out->subDay()
            );

            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }
        }

        $this->blockedDates = $dates;
    }

    public function updatedPropertyId()
    {
        $this->loadBlockedDates();
    }

    public function updatedCheckIn($value)
    {
        $this->check_in = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function updatedCheckOut($value)
    {
        $this->check_out = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
    }

    public function save()
    {
        $data = $this->validate([
            'property_id' => 'required',
            'guest_name' => 'required',
            'guest_email' => 'required|email',
            'guest_phone' => 'nullable',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'guests' => 'required|integer|min:1',
            'status' => 'required',
        ]);

        $data['nights'] = Carbon::parse($this->check_in)
            ->diffInDays($this->check_out);

        $data['total_value'] =
            ($this->daily_total ?? 0) +
            ($this->cleaning_fee ?? 0);

        PropertyReservation::updateOrCreate(
            ['id' => $this->reservation?->id],
            $data
        );

        return redirect()->route('reservations.index')
            ->with('success', 'Reserva salva com sucesso');
    }

    public function render()
    {
        return view('livewire.dashboard.reservations.reservation-form', [
            'properties' => Property::pluck('title', 'id')
        ]);
    }
}
