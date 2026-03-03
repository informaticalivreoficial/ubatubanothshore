<?php

namespace App\Livewire\Dashboard\Menu;

use App\Models\Menu;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public int $perPage = 25;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    protected $updatesQueryString = ['search'];

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    protected $listeners = ['menu-saved' => '$refresh'];

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
        $menu = Menu::with('children')->findOrFail($id);

        $newStatus = ! (bool) $menu->status;

        // atualiza a categoria clicada
        $menu->update([
            'status' => $newStatus,
        ]);

        // se for PAI, replica para as filhas
        if ($menu->children->isNotEmpty()) {
            $menu->children()->update([
                'status' => $newStatus,
            ]);
        }
    }

    public function setDeleteId($id)
    {        
        $menu = Menu::findOrFail($id);

        if($menu->children()->count() > 0){
            $this->dispatch('swal', [
                'title' => 'Erro!',
                'icon'  => 'error',
                'text'  => 'Não é possível excluir um link que possui sublinks.',
            ]);
            return;
        }        

        $this->dispatch('swal:confirm', [
            'title' => 'Excluir ' . ($menu->children()->count() > 0 ? 'SubLink' : 'Link'),
            'text' => (isset($text) ? $text : 'Essa ação não pode ser desfeita.'),
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteLink',
            'confirmParams' => [$id],
        ]);       
    }

    #[On('deleteLink')]
    public function deleteLink($id): void
    {
        $menu = Menu::findOrFail($id);

        $menu->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => ($menu->children()->count() > 0 ? 'SubLink' : 'Link') . ' excluído com sucesso.',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);
    }

    public function render()
    {
        $title = 'Lista de Links';
        $searchableFields = ['title','type','url'];
        $menus = Menu::query()
            ->whereNull('id_pai')
            ->when($this->search, function ($query) use ($searchableFields) {
                $query->where(function ($q) use ($searchableFields) {
                    foreach ($searchableFields as $field) {
                        $q->orWhere($field, 'LIKE', "%{$this->search}%");
                    }
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        return view('livewire.dashboard.menu.index', [
            'menus' => $menus
        ])->with('title', $title);
    }
}
