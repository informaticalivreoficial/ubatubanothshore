<?php

namespace App\Livewire\Web;

use App\Mail\AdminReservationNotification;
use App\Mail\ClientReservationReceived;
use App\Models\Property;
use App\Models\PropertyReservation;
use App\Models\User;
use App\Notifications\NewReservationNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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

    public $nights;
    public $subtotal;
    public $cleaning_fee;
    public $total;

    public $editingTrip = false;

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
                    'password' => bcrypt(str()->random(10))
                ]
            );

            //dd($this->total);
            // 📅 Criar reserva
            $reservation = PropertyReservation::create([
                'property_id' => $this->property->id,
                'user_id' => $client->id,
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
                'status' => 'pending',
            ]);

            

            $admin = User::where('admin', true)->first();

            if ($admin) {
                $admin->notify(new NewReservationNotification($reservation));

                Mail::to($admin->email)
                    ->send(new AdminReservationNotification($reservation));
            }            

            // 📧 Email para cliente
            //Mail::to($client->email)
            //    ->send(new ClientReservationReceived($reservation));
        });
        dd('reservation success');
        //return redirect()->route('reservation.success');
    }

    private function validateAvailability()
    {
        $exists = PropertyReservation::where('property_id', $this->property->id)
            ->where('status', '!=', 'cancelled')
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

    private function calculateTotal()
    {
        if (!$this->check_in || !$this->check_out) {
            return;
        }

        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);

        if ($checkOut->lte($checkIn)) {
            return;
        }

        $this->nights = $checkIn->diffInDays($checkOut);

        $this->cleaning_fee = $this->property->cleaning_fee ?? 0;

        $this->subtotal = $this->property->rental_value * $this->nights;

        // 🔥 EXTRA GUESTS (MESMA LÓGICA DO BOOKING FORM)
        $this->extraGuests = max(
            0,
            (int) $this->guests - (int) $this->property->aditional_person
        );

        $this->extraTotal =
            $this->extraGuests *
            $this->property->value_aditional *
            $this->nights;

        // 💰 TOTAL FINAL
        $this->total =
            $this->subtotal +
            $this->extraTotal +
            $this->cleaning_fee;
    }

    public function render()
    {
        return view('livewire.web.checkout-page');
    }
}
