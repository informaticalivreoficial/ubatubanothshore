<?php

namespace App\Livewire\Dashboard\Properties;

use App\Models\Config;
use App\Models\Property;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Intervention\Image\Facades\Image;

class Properties extends Component
{
    use WithPagination;

    // Quantidade de itens por página
    public int $perPage = 24;

    protected $paginationTheme = 'bootstrap';

    public string $search = '';

    protected $updatesQueryString = ['search'];

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    #{Url}
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->perPage += 12; // aumenta a quantidade de itens carregados
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

    public function render()
    {
        $title = 'Lista de Imóveis';
        $searchableFields = ['title','city','state','reference','type','neighborhood'];
        $properties = Property::query()
            ->when($this->search, function ($query) use ($searchableFields) {
                $query->where(function ($q) use ($searchableFields) {
                    foreach ($searchableFields as $field) {
                        $q->orWhere($field, 'LIKE', "%{$this->search}%");
                    }
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        return view('livewire.dashboard.properties.properties',[
            'properties' => $properties
        ])->with('title', $title);
    }

    public function toggleStatus($id)
    {              
        $property = Property::findOrFail($id);
        $property->status = !$property->status;        
        $property->save();
    }

    public function toggleHighlight(Property $property)
    {
        $property->highlight = !$property->highlight;
        $property->save();        
    }

    public function setDeleteId($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => 'Excluir Imóvel',
            'text' => 'Essa ação não pode ser desfeita.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sim, excluir',
            'cancelButtonText' => 'Cancelar',
            'confirmEvent' => 'deleteProperty',
            'confirmParams' => [$id],
        ]);       
    }

    #[On('deleteProperty')]
    public function deleteProperty($id): void
    {
        $property = Property::findOrFail($id);

        $property->delete();

        $this->dispatch('swal', [
            'title' => 'Excluído!',
            'text'  => 'Imóvel e todas as imagens foram removidas!',
            'icon'  => 'success',
            'timer' => 2000,
            'showConfirmButton' => false,
        ]);                
    }    

    public function applyWatermark(Property $property)
    {
        // Se já estiver marcada, não faz nada
        if ($property->display_marked_water) {
            return;
        }

        // Pega a marca d'água da tabela config
        $config = Config::first(); // ou filtro específico se tiver mais de uma
        if (!$config || !$config->watermark) {
            $this->dispatch('swal', [
                'title' => 'Erro!',
                'icon'  => 'error',
                'text'  => 'Nenhuma marca d’água configurada.'
            ]);
            return;
        }
        
        $watermarkPath = storage_path('app/public/' . $config->watermark);
        if (!file_exists($watermarkPath)) {
            $this->dispatch('swal', [
                'title' => 'Erro!',
                'icon'  => 'error',
                'text'  => 'Arquivo de marca d’água não encontrado.'
            ]);
            return;
        }

        foreach ($property->images as $image) {
            $imagePath = storage_path('app/public/' . $image->path);

            if (file_exists($imagePath)) {
                $img = Image::make($imagePath);
                $img->insert($watermarkPath, 'bottom-right', 20, 20); // posição
                $img->save($imagePath);
            }
        }

        // Atualiza o campo display_marked_water
        $property->update(['display_marked_water' => true]);

        $this->dispatch('swal', [
            'title' => 'Marca d’água aplicada!',
            'icon'  => 'success',
        ]);

        $property->refresh();
    }
}
