<?php

namespace App\Livewire\Components;

use Livewire\Component;

class NotificationsBadge extends Component
{
    public function render()
    {
        return view('livewire.components.notifications-badge', [
            'unreadNotificationsCount' => auth()->user()->unreadNotifications->count(),
        ]);
    }
}
