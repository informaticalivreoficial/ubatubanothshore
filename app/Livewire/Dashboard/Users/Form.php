<?php

namespace App\Livewire\Dashboard\Users;

use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Form extends Component
{
    use WithFileUploads;

    public ?User $user = null;

    public $foto; // Propriedade para armazenar a foto temporariamente
    public $fotoUrl; // Propriedade para armazenar o caminho da foto após o upload
    
    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'birthday' => 'required|date_format:d/m/Y|before:today',
            'cpf' => 'required|cpf|unique:users,cpf,' . ($this->user?->id),
            'email' => 'required|email|unique:users,email,' . ($this->user?->id),
            'cell_phone' => 'required|celular_com_ddd',
            'zipcode' => 'required|string|max:10',
        ];
    }       

    //Informations about
    public $name, $birthday, $gender, $naturalness, $civil_status, $code ,$avatar, $information;    
    
    //Documents
    public $cpf, $rg, $rg_expedition;

    //Address
    public $zipcode = '', $street, $neighborhood, $city, $state, $complement, $number;

    //Contact
    public $phone, $cell_phone, $whatsapp, $email, $additional_email, $telegram;

    //Social
    public $facebook, $instagram, $linkedin;

    //Function
    public bool $admin = false;
    public bool $client = false;
    public bool $editor = false;
    public bool $superadmin = false;

    public $password;
    public $password_confirmation;

    public $errorMessage;

    public function mount(?User $user = null)
    {
        $this->user = $user; 
        if($this->user){
            $this->name = $user->name;
            $this->code = $user->code;
            $this->avatar = $user->avatar;
            $this->birthday = $user->birthday;
            $this->gender = $user->gender ?? 'masculino';
            $this->naturalness = $user->naturalness;
            $this->civil_status = $user->civil_status;
            $this->rg = $user->rg;
            $this->rg_expedition = $user->rg_expedition;
            $this->cpf = $user->cpf;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->cell_phone = $user->cell_phone;
            $this->whatsapp = $user->whatsapp;
            $this->additional_email = $user->additional_email;
            $this->telegram = $user->telegram;
            $this->number = $user->number;
            $this->zipcode = $user->zipcode;
            $this->street = $user->street;
            $this->neighborhood = $user->neighborhood;
            $this->city = $user->city;
            $this->state = $user->state;
            $this->complement = $user->complement;
            $this->facebook = $user->facebook;
            $this->instagram = $user->instagram;
            $this->linkedin = $user->linkedin;
            $this->information = $user->information;
            $this->admin = (bool) $user->admin;
            $this->client = (bool) $user->client;
            $this->editor = (bool) $user->editor;
            $this->superadmin = (bool) $user->superadmin;
        }
    }

    public function render()
    {
        return view('livewire.dashboard.users.form');
    }

    public function save()
    {
        $this->validate();

        // Upload da foto
        if ($this->foto) {
            if ($this->user && $this->avatar && Storage::disk('public')->exists($this->avatar)) {
                Storage::disk('public')->delete($this->avatar);
            }

            $caminhoFoto = $this->foto->store('client', 'public');
        }

        $data = [
            'name' => $this->name,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'naturalness' => $this->naturalness,
            'civil_status' => $this->civil_status,
            'rg' => $this->rg,
            'rg_expedition' => $this->rg_expedition,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'phone' => $this->phone,
            'cell_phone' => $this->cell_phone,
            'whatsapp' => $this->whatsapp,
            'additional_email' => $this->additional_email,
            'telegram' => $this->telegram,
            'number' => $this->number,
            'zipcode' => $this->zipcode,
            'street' => $this->street,
            'neighborhood' => $this->neighborhood,
            'city' => $this->city,
            'state' => $this->state,
            'complement' => $this->complement,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'linkedin' => $this->linkedin,
            'admin' => (bool) $this->admin,
            'client' => (bool) $this->client,
            'editor' => (bool) $this->editor,
            'superadmin' => (bool) $this->superadmin,
            'avatar' => $caminhoFoto ?? $this->avatar,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->user) {
            $this->user->update($data);
        } else {
            $this->user = User::create($data);
            return redirect()->route('users.edit', $this->user);
        }

        $this->dispatch('swal', [
            'title' => 'Sucesso!',
            'text' => ($this->user ? 'Usuário atualizado com sucesso!' : 'Usuário cadastrado com sucesso!'),
            'icon' => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);

    }    

    public function updatedZipcode(string $value)
    {        
        $this->zipcode = preg_replace('/[^0-9]/', '', $value);

        if(strlen($this->zipcode) === 8){
            $response = Http::get("https://viacep.com.br/ws/{$this->zipcode}/json/")->json();            
            if(!isset($response['erro'])){                
                $this->street = $response['logradouro'] ?? '';
                $this->neighborhood = $response['bairro'] ?? '';
                $this->state = $response['uf'] ?? '';
                $this->city = $response['localidade'] ?? '';
                $this->complement = $response['complemento'] ?? '';      
            }else{                
                $this->addError('zipcode', 'CEP não encontrado.'); 
            }
        }
    }

    // public function updatedPasswordconfirmation(string $value)
    // {
    //     if ($this->password !== $this->password_confirmation) {
    //         session()->flash('erro', 'As senhas não coincidem!');
    //         return;
    //     }
    // }

    public function updatedFoto()
    {
        $this->validateOnly('foto'); // Valida apenas o campo 'foto'
        $this->fotoUrl = $this->foto->temporaryUrl(); // Gera a URL temporária da foto
    }

    // public function atualizarData($valor)
    // {
    //     $this->birthday = $valor;
    // }

}
