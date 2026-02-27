<?php

namespace App\Livewire\Web;

use App\Mail\Atendimento;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactForm extends Component
{
    public $nome;
    public $email;
    public $mensagem;
    public $success = false;

    // Campos honeypot (anti-spam)
    public $bairro;
    public $cidade;

    protected $rules = [
        'nome' => 'required|min:3',
        'email' => 'required|email',
        'mensagem' => 'required|min:10',
    ];

    public function render()
    {
        return view('livewire.web.contact-form');
    }

    public function submit()
    {
        if (!empty($this->bairro) || !empty($this->cidade)) {
            return;
        }

        $validated = $this->validate();
        
        Mail::send(new Atendimento($validated));
        
        $this->reset(['nome', 'email', 'mensagem']);
        $this->success = true;
    }
}
