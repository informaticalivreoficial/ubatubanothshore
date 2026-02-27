<?php

namespace App\Livewire\Dashboard\Users;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Masmerise\Toaster\Toaster;

class Users extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    public bool $updateMode = false;
        
    #{Url}
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }    

    #[Title('Clientes')]
    public function render()
    {
        $users = \App\Models\User::query()->when($this->search, function($query){
                        $query->orWhere('name', 'LIKE', "%{$this->search}%");
                        $query->orWhere('email', "%{$this->search}%");
                    })->where('client', 1)->orderBy($this->sortField, $this->sortDirection)->paginate(35);
        return view('livewire.dashboard.users.users',[
            'users' => $users
        ]);
    }

    public function setDeleteId($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Cliente?',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteUser',
            'confirmParams' => [$id],
        ]);        
    }
    #[On('deleteUser')]
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => 'Cliente excluído com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function toggleStatus($id)
    {              
        $user = User::findOrFail($id);
        $user->status = !$user->status;        
        $user->save();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->dispatch('userId');
        $this->updateMode = true;
    }

}
