<?php

namespace App\Livewire\Dashboard\Reservations;

use App\Models\Property;
use App\Models\PropertyReservation;
use Carbon\Carbon;
use Livewire\Component;

class ReservationForm extends Component
{
    public ?PropertyReservation $reservation = null;

    public $property;
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

    public $maxGuests = 1;

    public function mount(?PropertyReservation $reservation = null)
    {
        if ($reservation) {

            $this->reservation = $reservation;

            // Preenche primeiro os dados
            $this->fill($reservation->toArray());

            // Agora property_id já existe
            $this->blockedDates = PropertyReservation::where('property_id', $this->property_id)
                ->get()
                ->map(fn ($r) => [
                    'from' => $r->check_in->format('Y-m-d'),
                    'to' => $r->check_out->format('Y-m-d'),
                ])
                ->toArray();

            $this->check_in = $reservation->check_in?->format('Y-m-d');
            $this->check_out = $reservation->check_out?->format('Y-m-d');
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

    public function updatedCheckIn($value)
    {
        if (!$value || strlen($value) !== 10) {
            return;
        }

        try {
            $this->check_in = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        } catch (\Exception $e) {
            // ignora enquanto a data estiver incompleta
        }
    }

    public function updatedCheckOut($value)
    {
        if (!$value || strlen($value) !== 10) {
            return;
        }

        try {
            $this->check_out = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        } catch (\Exception $e) {
            // ignora enquanto a data estiver incompleta
        }
    }

    public function save()
    {
        $property = Property::find($this->property_id);

        $data = $this->validate([
            'property_id' => 'required',
            'guest_name' => 'required',
            'guest_email' => 'required|email',
            'guest_phone' => 'nullable',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'guests' => 'required|integer|min:1|max:' . $property->capacity,
            'status' => 'required',
        ]);

        // 🔥 recalcula tudo antes de salvar
        $property = Property::find($this->property_id);
        $totals = PropertyReservation::calculateTotals(
            $property,
            $this->check_in,
            $this->check_out,
            $this->guests
        );
        $data = array_merge($data, $totals);

        //$data['extra_total'] = $this->extraTotal;

        $reservation = PropertyReservation::updateOrCreate(
            ['id' => $this->reservation?->id],
            $data
        );

        $this->reservation = $reservation;

        $foiCriacao = $reservation->wasRecentlyCreated;

        $mensagem = $foiCriacao
            ? 'Reserva cadastrada com sucesso!'
            : 'Reserva atualizada com sucesso!';

        $this->dispatch('swal:success', [
            'title' => 'Sucesso!',
            'text' => $mensagem,
            'timer' => 2000,
        ]);
    }

    public function calculateTotal()
    {
        if (!$this->check_in || !$this->check_out || !$this->property_id) {
            return;
        }

        $property = Property::find($this->property_id);

        $checkin = Carbon::parse($this->check_in);
        $checkout = Carbon::parse($this->check_out);

        $this->nights = $checkin->diffInDays($checkout);

        $daily = $property->rental_value * $this->nights;

        $extra = 0;

        if ($this->guests > $property->aditional_person) {
            $extraGuests = $this->guests - $property->aditional_person;
            $extra = $extraGuests * $property->value_aditional * $this->nights;
        }

        $this->total_value = $daily + $extra + $this->cleaning_fee;
    }

    public function updated($field)
    {
        if (in_array($field, [
            'check_in',
            'check_out',
            'guests',
            'cleaning_fee',
        ])) {
            $this->calculateTotal();
        }
    }

    public function updatedPropertyId($value)
    {
        $this->loadBlockedDates();

        $property = Property::find($value);

        if (!$property) {
            return;
        }

        $this->daily_total = $property->rental_value;
        $this->cleaning_fee = $property->cleaning_fee;

        $this->maxGuests = $property->capacity;

        if ($this->guests > $this->maxGuests) {
            $this->guests = $this->maxGuests;
        }

        $this->calculateTotal();
    }

    public function updatedGuests($value)
    {
        if (!$this->property_id) {
            return;
        }

        $property = Property::find($this->property_id);

        if (!$property) {
            return;
        }

        if ($value > $property->capacity) {

            $this->addError(
                'guests',
                "Esta propriedade permite no máximo {$property->capacity} hóspedes."
            );

            return;
        }

        if ($value < 1) {
            return;
        }

        $this->resetErrorBag('guests');

        $this->calculateTotal();
    }

    public function render()
    {
        return view('livewire.dashboard.reservations.reservation-form', [
            'properties' => Property::pluck('title', 'id')
        ]);
    }
}
