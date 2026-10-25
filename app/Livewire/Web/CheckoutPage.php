<?php

namespace App\Livewire\Web;

use App\Mail\ReservationFormLinkMail;
use App\Models\Property;
use App\Models\PropertyBlockedDate;
use App\Models\PropertyReservation;
use App\Models\User;
use App\Notifications\NewReservationNotification;
use App\Support\PropertyPricing;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CheckoutPage extends Component
{
    public Property $property;

    public $check_in;

    public $check_out;

    public $guests;

    public $extraGuests = 0;

    public $extraTotal = 0;

    public $name;

    public $email;

    public $phone;

    public $notes;

    public $review_token;

    public $nights;

    public $subtotal;

    public $cleaning_fee;

    public $iptu_fee;

    public $total;

    public $editingTrip = false;

    public $success = false; // flag para exibir a mensagem de sucesso

    public $disabledDates = [];

    public $seasonApplied = false;

    public function mount(Property $property, $check_in, $check_out, $guests)
    {
        $this->property = $property;
        $this->check_in = $check_in;
        $this->check_out = $check_out;
        $this->guests = $guests;

        if ($property->isBookingClosed()) {
            return redirect()->route('web.property', $property->slug);
        }

        // validação básica
        if (! $check_in || ! $check_out) {
            return redirect()->route('web.property', $property->slug);
        }

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

        $this->calculateTotal();
    }

    public function updated($field)
    {
        if (in_array($field, ['check_in', 'check_out', 'guests'])) {
            $this->calculateTotal();
        }
    }

    public function confirmReservation()
    {
        $this->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1|max:'.$this->maxGuests(),
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required',
        ]);

        DB::transaction(function () {

            // 🔒 Revalidar disponibilidade
            $this->validateAvailability();

            // 👤 Criar ou buscar cliente
            $client = User::firstOrCreate(
                ['email' => $this->email],
                [
                    'name' => $this->name,
                    'client' => true,
                    'phone' => $this->phone,
                    'status' => 1,
                    'password' => bcrypt(str()->random(10)),
                ]
            );

            // 📅 Criar reserva
            $reservation = PropertyReservation::create([
                'property_id' => $this->property->id,
                'user_id' => $client->id,
                'review_token' => Str::uuid(),
                'guest_name' => $this->name,
                'guest_email' => $this->email,
                'guest_phone' => $this->phone,
                'check_in' => $this->check_in,
                'check_out' => $this->check_out,
                'nights' => $this->nights,
                'notes' => $this->notes,
                'guests' => $this->guests,
                'cleaning_fee' => $this->cleaning_fee,
                'iptu_fee' => $this->iptu_fee,
                'origin' => 'site',
                'daily_total' => $this->subtotal,
                'total_value' => $this->total,
                'status' => 'waiting_payment',
                'expired_at' => now()->addMinutes(30),
            ]);

            $admin = User::where('admin', true)->first();

            if ($admin) {
                $admin->notify(new NewReservationNotification($reservation));
            }

            // 📧 Email para cliente
            Mail::to($this->email)->send(new ReservationFormLinkMail($reservation));
        });

        $this->reset(['name', 'email', 'phone', 'notes']);
        $this->success = true;
        $this->dispatch('reservation-success');
    }

    private function validateAvailability()
    {
        if (! $this->property->isAvailable($this->check_in, $this->check_out)) {
            throw ValidationException::withMessages([
                'check_in' => 'Estas datas não estão mais disponíveis.',
            ]);
        }

        if ($this->nights < $this->property->min_nights) {
            throw ValidationException::withMessages([
                'check_in' => "Esta propriedade exige mínimo de {$this->property->min_nights} noite(s).",
            ]);
        }

        $deadline = $this->property->bookingDeadline();

        if ($deadline && Carbon::parse($this->check_out)->gt($deadline)) {
            throw ValidationException::withMessages([
                'check_in' => 'A reserva não pode ultrapassar a data disponível.',
            ]);
        }
    }

    public function maxGuests(): int
    {
        return (int) $this->property->capacity + (int) $this->property->aditional_person;
    }

    public function calculateTotal()
    {
        if (! $this->check_in || ! $this->check_out) {
            return;
        }

        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);

        if ($checkOut->lte($checkIn)) {
            return;
        }

        $this->guests = max(1, min((int) $this->guests, $this->maxGuests()));

        $quote = PropertyPricing::quote($this->property, $checkIn, $checkOut, (int) $this->guests);

        $this->nights = $quote['nights'];
        $this->subtotal = $quote['daily_total'];
        $this->extraGuests = $quote['extra_guests'];
        $this->extraTotal = $quote['extra_total'];
        $this->cleaning_fee = $quote['cleaning_fee'];
        $this->iptu_fee = $quote['iptu_fee'];
        $this->total = $quote['total_value'];
        $this->seasonApplied = $quote['season_applied'];
    }

    public function increaseGuests()
    {
        if ($this->guests < $this->maxGuests()) {
            $this->guests++;
            $this->calculateTotal();
        }
    }

    public function decreaseGuests()
    {
        if ($this->guests > 1) {
            $this->guests--;
            $this->calculateTotal(); // 👈 recalcula
        }
    }

    public function render()
    {
        return view('livewire.web.checkout-page');
    }
}
