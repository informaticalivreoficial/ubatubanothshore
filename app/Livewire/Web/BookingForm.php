<?php

namespace App\Livewire\Web;

use App\Models\Property;
use App\Models\PropertyBlockedDate;
use App\Models\PropertyReservation;
use App\Support\PropertyPricing;
use Carbon\Carbon;
use Livewire\Component;

class BookingForm extends Component
{
    public Property $property;

    public $check_in;

    public $check_out;

    public $guests = 1;

    public $nights = 0;

    public $dailyTotal = 0;

    public $total = 0;

    public $extraGuests = 0;

    public $extraTotal = 0;

    public $iptuFee = 0;

    public $dateError = null;

    public $disabledDates = [];

    public $seasonApplied = false;

    public $seasonNights = 0;

    public $baseNights = 0;

    public $seasonMinNightsError = null;

    public $bookingClosed = false;

    public $maxBookingDate = null;

    public function mount(Property $property)
    {
        $this->property = $property;
        $this->bookingClosed = $property->isBookingClosed();
        $this->maxBookingDate = $property->maxBookingDate();

        $dates = [];

        // 🔹 Datas de reservas
        $reservations = PropertyReservation::where('property_id', $property->id)
            ->where('status', '!=', 'cancelled')
            ->get();

        foreach ($reservations as $reservation) {

            $period = Carbon::parse($reservation->check_in)
                ->daysUntil(Carbon::parse($reservation->check_out));

            foreach ($period as $date) {
                $dates[] = $date->format('Y-m-d');
            }
        }

        // 🔴 Datas bloqueadas manualmente
        $blockedDates = PropertyBlockedDate::where('property_id', $property->id)
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->toArray();

        // 🔥 Junta tudo e remove duplicados
        $this->disabledDates = array_values(
            array_unique(array_merge($dates, $blockedDates))
        );
    }

    public function updated($field)
    {
        if (in_array($field, ['check_in', 'check_out'])) {
            $this->calculate();
        }
    }

    public function updatedGuests()
    {
        $this->calculate();
    }

    public function increaseGuests()
    {
        if ($this->guests < ($this->property->capacity + $this->property->aditional_person)) {
            $this->guests++;
            $this->calculate(); // 👈 recalcula
        }
    }

    public function decreaseGuests()
    {
        if ($this->guests > 1) {
            $this->guests--;
            $this->calculate(); // 👈 recalcula
        }
    }

    public function calculate()
    {
        $this->dateError = null;

        if (! $this->check_in || ! $this->check_out) {
            $this->reset(['nights', 'dailyTotal', 'total', 'extraGuests', 'extraTotal', 'iptuFee', 'seasonApplied', 'seasonNights', 'baseNights']);

            return;
        }

        if ($this->bookingClosed) {
            $this->dateError = 'As reservas para este imóvel estão encerradas.';
            $this->reset(['nights', 'dailyTotal', 'total', 'extraGuests', 'extraTotal', 'iptuFee', 'seasonApplied', 'seasonNights', 'baseNights']);

            return;
        }

        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);

        if ($checkOut->lte($checkIn)) {
            $this->dateError = 'A data de check-out deve ser posterior ao check-in.';
            $this->reset(['nights', 'dailyTotal', 'total', 'extraGuests', 'extraTotal', 'iptuFee', 'seasonApplied', 'seasonNights', 'baseNights']);

            return;
        }

        if ($checkIn->lt(today())) {
            $this->dateError = 'A data de check-in não pode estar no passado.';
            $this->reset(['nights', 'dailyTotal', 'total', 'extraGuests', 'extraTotal', 'iptuFee', 'seasonApplied', 'seasonNights', 'baseNights']);

            return;
        }

        $bookingDeadline = $this->property->bookingDeadline();

        if ($bookingDeadline && $checkOut->gt($bookingDeadline)) {
            $this->dateError = 'A reserva não pode ultrapassar a data disponível.';
            $this->reset(['nights', 'dailyTotal', 'total', 'extraGuests', 'extraTotal', 'iptuFee', 'seasonApplied', 'seasonNights', 'baseNights']);

            return;
        }

        $this->nights = $checkIn->diffInDays($checkOut);

        if ($this->nights < $this->property->min_nights) {
            $this->dateError = "Esta propriedade exige mínimo de {$this->property->min_nights} noite(s).";
            $this->reset(['dailyTotal', 'total', 'extraGuests', 'extraTotal', 'iptuFee', 'seasonApplied', 'seasonNights', 'baseNights']);

            return;
        }

        // ✅ Temporada precisa ser integralmente incluída na reserva
        $seasonConflict = $this->property->seasonPeriodConflict($checkIn, $checkOut);

        if ($seasonConflict) {
            $this->dateError = $seasonConflict->coverageMessage();
            $this->reset(['dailyTotal', 'total', 'extraGuests', 'extraTotal', 'iptuFee', 'seasonApplied', 'seasonNights', 'baseNights']);

            return;
        }

        $quote = PropertyPricing::quote($this->property, $checkIn, $checkOut, (int) $this->guests);

        $this->nights = $quote['nights'];
        $this->dailyTotal = $quote['daily_total'];
        $this->extraGuests = $quote['extra_guests'];
        $this->extraTotal = $quote['extra_total'];
        $this->iptuFee = $quote['iptu_fee'];
        $this->total = $quote['total_value'];
        $this->seasonApplied = $quote['season_applied'];
        $this->seasonNights = $quote['season_nights'];
        $this->baseNights = $quote['base_nights'];
    }

    public function goToCheckout()
    {
        $this->calculate();

        if (! $this->check_in || ! $this->check_out) {
            $this->dateError = 'Selecione as datas da reserva.';

            return;
        }

        if ($this->dateError) {
            return;
        }

        if ($this->nights < $this->property->min_nights) {
            $this->dateError = "Esta propriedade exige mínimo de {$this->property->min_nights} noite(s).";

            return;
        }

        return redirect()->route('web.checkout', [
            'property' => $this->property->id,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'guests' => $this->guests,
        ]);
    }

    public function render()
    {
        return view('livewire.web.booking-form');
    }
}
