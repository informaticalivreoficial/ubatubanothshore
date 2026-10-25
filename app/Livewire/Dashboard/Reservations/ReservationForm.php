<?php

namespace App\Livewire\Dashboard\Reservations;

use App\Models\Property;
use App\Models\PropertyReservation;
use App\Support\PropertyPricing;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
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

    public $extra_total = 0;

    public $cleaning_fee = 0;

    public $iptu_fee = 0;

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
        if (! $this->property_id) {
            return;
        }

        $reservations = PropertyReservation::where('property_id', $this->property_id)
            ->when($this->reservation?->id, fn ($q) => $q->where('id', '!=', $this->reservation->id)
            )
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $dates = [];

        foreach ($reservations as $reservation) {

            $period = CarbonPeriod::create(
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
        if (! $value || strlen($value) !== 10) {
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
        if (! $value || strlen($value) !== 10) {
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
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:'.$property->capacity,
            'status' => 'required',
        ]);

        // 🔥 recalcula tudo antes de salvar
        $property = Property::find($this->property_id);
        $quote = PropertyPricing::quote(
            $property,
            $this->check_in,
            $this->check_out,
            (int) $this->guests
        );

        $data['nights'] = $quote['nights'];
        $data['daily_total'] = $quote['daily_total'];
        $data['cleaning_fee'] = (float) $this->cleaning_fee;
        $data['iptu_fee'] = (float) $this->iptu_fee;
        $data['total_value'] = $quote['daily_total']
            + $quote['extra_total']
            + (float) $this->cleaning_fee
            + (float) $this->iptu_fee;

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
        if (! $this->check_in || ! $this->check_out || ! $this->property_id) {
            return;
        }

        $property = Property::find($this->property_id);

        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);

        if ($checkOut->lte($checkIn)) {
            return;
        }

        $quote = PropertyPricing::quote($property, $checkIn, $checkOut, (int) $this->guests);

        $this->nights = $quote['nights'];
        $this->daily_total = $quote['daily_total'];
        $this->extra_total = $quote['extra_total'];
        $this->iptu_fee = $quote['iptu_fee'];
        $this->total_value = $quote['daily_total']
            + $quote['extra_total']
            + (float) $this->cleaning_fee
            + $quote['iptu_fee'];
    }

    public function updated($field)
    {
        if (in_array($field, [
            'check_in',
            'check_out',
            'guests',
            'cleaning_fee',
            'iptu_fee',
            'daily_total', // ✅
            'nights',      // ✅
        ])) {
            $this->recalculateFromInputs();
        }
    }

    public function recalculateFromInputs()
    {
        $this->total_value = $this->daily_total + $this->extra_total + $this->cleaning_fee + $this->iptu_fee;
    }

    public function updatedPropertyId($value)
    {
        $this->loadBlockedDates();

        $property = Property::find($value);

        if (! $property) {
            return;
        }

        $this->daily_total = $property->priceForGuests($this->guests);
        $this->cleaning_fee = $property->cleaning_fee;
        $this->iptu_fee = $property->iptu_fee ?? 0;

        $this->maxGuests = $property->capacity;

        if ($this->guests > $this->maxGuests) {
            $this->guests = $this->maxGuests;
        }

        $this->calculateTotal();
    }

    public function updatedGuests($value)
    {
        if (! $this->property_id) {
            return;
        }

        $property = Property::find($this->property_id);

        if (! $property) {
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
            'properties' => Property::pluck('title', 'id'),
        ]);
    }
}
