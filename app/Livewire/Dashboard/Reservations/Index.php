<?php

namespace App\Livewire\Dashboard\Reservations;

use App\Models\PropertyReservation;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $status = '';

    public $showModal = false;
    public $selectedReservation = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updateStatus($id, $status)
    {
        PropertyReservation::where('id', $id)
            ->update(['status' => $status]);
    }

    public function showReservation($id)
    {
        $this->selectedReservation = PropertyReservation::with('property')
            ->findOrFail($id);

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->reset('showModal', 'selectedReservation');
    }

    public function sendPaymentLink($id)
    {
        $reservation = PropertyReservation::findOrFail($id);

        // aqui você pode gerar link de pagamento
        // ou enviar email

        // exemplo simples
        Mail::to($reservation->guest_email)
            ->send(new ReservationPaymentLinkMail($reservation));

        $this->dispatch('notify', 'Link enviado para o cliente!');
    }

    public function render()
    {
        $reservations = PropertyReservation::query()
            ->with(['property'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('guest_name', 'like', "%{$this->search}%")
                      ->orWhere('guest_email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->status, fn ($q) =>
                $q->where('status', $this->status)
            )
            ->latest()
            ->paginate(25);

        return view('livewire.dashboard.reservations.index', [
            'reservations' => $reservations,
        ]);
    }
}
