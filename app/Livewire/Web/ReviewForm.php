<?php

namespace App\Livewire\Web;

use App\Helpers\Renato;
use App\Models\PropertyReservation;
use App\Models\PropertyReview;
use Livewire\Component;

class ReviewForm extends Component
{
    public $reservation;
    public $rating = 0;
    public $comment;

    public function mount($token)
    {
        $this->reservation = PropertyReservation::where('review_token', $token)->firstOrFail();

        // impedir avaliação duplicada
        if ($this->reservation->review) {
            abort(403, 'Avaliação já enviada.');
        }
    }

    public function save()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|min:10|max:1000'
        ]);

        PropertyReview::create([
            'property_id' => $this->reservation->property_id,
            'reservation_id' => $this->reservation->id,
            'guest_name' => $this->reservation->guest_name,
            'guest_email' => $this->reservation->guest_email,
            'rating' => $this->rating,
            'comment' => $this->comment,
        ]);

        $name = Renato::getPrimeiroNome($this->reservation->guest_name);

        $this->dispatch('swal:success', [
            'title' => false,
            'text' => "Obrigado {$name} pela sua avaliação!",
            'timer' => 3000,
            'icon' => 'success',
            'showConfirmButton' => false,
            'redirectUrl' => route('web.home'),
        ]);

        $this->reset(['rating','comment']);
    }

    public function render()
    {
        return view('livewire.web.review-form');
    }
}
