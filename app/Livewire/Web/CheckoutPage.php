<?php

namespace App\Livewire\Web;

use App\Mail\AdminReservationNotification;
use App\Mail\ClientReservationReceived;
use App\Mail\ReservationFormLinkMail;
use App\Models\Property;
use App\Models\PropertyBlockedDate;
use App\Models\PropertyReservation;
use App\Models\User;
use App\Notifications\NewReservationNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

        // validação básica
        if (!$check_in || !$check_out) {
            return redirect()->route('web.property', $property->slug);
        }

        $dates = [];

        // 🔹 Datas de reservas
        $reservations = PropertyReservation::where('property_id', $property->id)
            ->whereIn('status', ['waiting_payment', 'confirmed'])
            ->where(function ($q) {
                $q->whereNull('expired_at')
                ->orWhere('expired_at','>', now());
            })
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
            ->map(fn($date) => Carbon::parse($date)->format('Y-m-d'))
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
            'name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required'
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
                    'password' => bcrypt(str()->random(10))
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
                'origin' => 'site',
                'daily_total' => $this->property->rental_value,
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
        $exists = PropertyReservation::where('property_id', $this->property->id)
            ->whereIn('status', ['waiting_payment', 'confirmed'])
            ->where(function ($query) {
                $query->where('check_in', '<', $this->check_out)
                    ->where('check_out', '>', $this->check_in);
            })
            ->exists();

        if ($exists) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'check_in' => 'Estas datas não estão mais disponíveis.'
            ]);
        }
    }

    public function calculateTotal()
    {
        if (!$this->check_in || !$this->check_out) {
            return;
        }

        $checkIn  = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);

        if ($checkOut->lte($checkIn)) {
            return;
        }

        $this->nights     = $checkIn->diffInDays($checkOut);
        $this->cleaning_fee = $this->property->cleaning_fee ?? 0;

        // ✅ Cálculo dia a dia considerando temporadas
        $seasons = $this->property->seasons()
            ->where('start_date', '<=', $checkOut->toDateString())
            ->where('end_date', '>=', $checkIn->toDateString())
            ->get();

        $this->subtotal = 0;
        $this->seasonApplied = false; // ✅ reseta
        $current = $checkIn->copy();

        while ($current->lt($checkOut)) {

            $season = $seasons->first(function ($s) use ($current) {
                return $current->between(
                    Carbon::parse($s->start_date),
                    Carbon::parse($s->end_date)
                );
            });

            if ($season) {
                $this->seasonApplied = true; // ✅ marca se usou temporada
                $this->subtotal += $season->price_per_day;
            } else {
                $this->subtotal += $this->property->rental_value;
            }

            $current->addDay();
        }

        // 🔥 Extra guests
        $this->extraGuests = max(
            0,
            (int) $this->guests - (int) $this->property->aditional_person
        );

        $this->extraTotal =
            $this->extraGuests *
            $this->property->value_aditional *
            $this->nights;

        // 💰 Total final
        $this->total = $this->subtotal + $this->extraTotal + $this->cleaning_fee;
    }

    public function increaseGuests()
    {
        if ($this->guests < ($this->property->capacity + $this->property->aditional_person)) {
            $this->guests++;
            $this->calculateTotal(); // 👈 recalcula
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
