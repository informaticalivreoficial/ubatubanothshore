<?php

namespace App\Livewire\Web;

use App\Mail\Consulta;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactProperty extends Component
{
    public $nome;
    public $email;
    public $success = false;

    // Campos honeypot (anti-spam)
    public $bairro;
    public $cidade;

    public ?string $reference = null;

    protected $rules = [
        'nome' => 'required|min:3',
        'email' => 'required|email'
    ];

    public function mount($reference = null)
    {
        $this->reference = $reference;
    }

    public function render()
    {
        return view('livewire.web.contact-property');
    }

    public function submit()
    {        
        if (!empty($this->bairro) || !empty($this->cidade)) {
            return;
        }

        $validated = $this->validate();
        $validated['reference'] = $this->reference;
                
        Mail::send(new Consulta($validated));
        
        $this->reset(['nome', 'email']);
        $this->success = true;
    }
}
