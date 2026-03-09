<?php

namespace App\Livewire\Dashboard\Properties;

use App\Models\Property;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;

class Calendar extends Component
{
    public Property $property;

    public function mount(Property $property)
    {
        $this->property = $property->load([
            'reservations',
            'blockedDates',
            'seasons'
        ]);
    }

    public function getEvents()
    {
        $events = [];

        // RESERVAS
        foreach ($this->property->reservations as $reservation) {

            if ($reservation->status !== 'cancelled') {

                $events[] = [
                    'title'  => 'Reserva: ' . $reservation->guest_name,
                    'start'  => $reservation->check_in,
                    'end'    => $reservation->check_out,
                    'allDay' => true,
                    'color'  => match($reservation->status) {
                        'confirmed' => '#22c55e',
                        'finished'  => '#3b82f6',
                        default     => '#ef4444',
                    },
                    'extendedProps' => [
                        'type' => 'reservation',
                        'url'  => route('reservations.edit', $reservation),
                    ],
                ];
            }
        }

        // BLOQUEIOS
        foreach ($this->property->blockedDates as $blocked) {

            $events[] = [
                'title'  => 'Bloqueado',
                'start'  => $blocked->date,
                'allDay' => true,
                'color'  => '#374151',
                'extendedProps' => [
                    'type' => 'blocked',
                ],
            ];
        }

        // TEMPORADAS
        foreach ($this->property->seasons as $season) {

            $start = \Carbon\Carbon::parse($season->start_date);
            $end   = \Carbon\Carbon::parse($season->end_date);

            while ($start <= $end) {
                $events[] = [
                    'title'           => 'R$ ' . number_format($season->price_per_day, 2, ',', '.'),
                    'start'           => $start->toDateString(),
                    'display'         => 'background',
                    'backgroundColor' => '#16a3b7', // cor fixa para todas as temporadas
                    'extendedProps'   => [
                        'type' => 'season',
                    ],
                ];

                $start->addDay();
            }
        }

        return $events;
    }

    public function blockDate($date)
    {
        $this->property->blockedDates()->create([
            'date' => $date,
            'reason' => 'Bloqueio manual'
        ]);

        $this->dispatch('refreshCalendar', $this->getEvents());
    }

    #[On('blockRange')]
    public function blockRange($start, $end)
    {
        $start = \Carbon\Carbon::parse($start);
        $end = \Carbon\Carbon::parse($end)->subDay();

        while ($start <= $end) {

            $this->property->blockedDates()->firstOrCreate([
                'date' => $start->toDateString()
            ], [
                'reason' => 'Bloqueio manual'
            ]);

            $start->addDay();
        }

        $this->dispatch('refreshCalendar', $this->getEvents());
    }

    #[On('unblockDate')]
    public function unblockDate($date)
    {
        $this->property->blockedDates()
            ->whereDate('date', $date)
            ->delete();

        $this->dispatch('refreshCalendar', $this->getEvents());
    }

    public function render()
    {
        return view('livewire.dashboard.properties.calendar', [
            'events' => $this->getEvents()
        ]);
    }
}
