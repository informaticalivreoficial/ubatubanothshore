<?php

namespace App\Livewire\Dashboard;

use App\Models\Post;
use App\Models\Property;
use Livewire\Component;

class Dashboard extends Component
{
    public $topproperties = [];
    public $topposts = [];

    public function render()
    {
        $propertyCount = Property::count();
        $propertyYearCount = Property::whereYear('created_at', now()->year)->count();

        $postsCount = Post::count();
        $postsYearCount = Post::whereYear('created_at', now()->year)->count();

        $this->topproperties = Property::orderBy('views', 'desc')
            ->take(6)
            ->get();
        $this->topposts = Post::orderBy('views', 'desc')
            ->take(5)
            ->get();
        
        $title = 'Painel de Controle';
        return view('livewire.dashboard.dashboard',[            
            'propertyCount' => $propertyCount,
            'propertyYearCount' => $propertyYearCount,
            'postsCount' => $postsCount,
            'postsYearCount' => $postsYearCount,
            'title' => $title,
            'topproperties' => $this->topproperties,
            'topposts' => $this->topposts,
        ]);
    }
}
