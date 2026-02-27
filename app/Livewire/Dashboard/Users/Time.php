<?php

namespace App\Livewire\Dashboard\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Time extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    public string $sortField = 'name';

    public string $sortDirection = 'asc';

    public function render()
    {
        $title = 'Time de Usuários';

        $users = User::query()
            ->where(function($query) {
                $query->where('editor', 1)->orWhere('admin', 1);
            })
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('email', 'LIKE', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.dashboard.users.time', [
            'users' => $users
        ])->with('title', $title);
    }

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

    public function toggleStatus($id)
    {              
        $user = User::findOrFail($id);
        $user->status = !$user->status;        
        $user->save();
    }

    public function setDeleteId($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Usuário?',
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
            'text'  => 'Usuário excluído com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }
}
