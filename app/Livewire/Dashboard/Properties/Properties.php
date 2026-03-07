<?php

namespace App\Livewire\Dashboard\Properties;

use App\Models\Config;
use App\Models\Property;
use App\Models\PropertyGb;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
            'text' => 'Essa ação não pode ser desfeita.!',
            'showConfirmButton' => false,
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

        $this->dispatch('swal:success', [
            'title' => 'Excluído!',
            'text' => 'Imóvel e todas as imagens foram removidas!',
            'timer' => 2000,
            'showConfirmButton' => false
        ]);              
    }    

    public function applyWatermark(Property $property)
    {      

        $config = Config::first();

        if (!$config || !$config->watermark) {
            $this->dispatch('swal:error', [
                'title' => false,
                'text' => 'Nenhuma marca d’água configurada!',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
            return;
        }

        $watermarkPath = storage_path('app/public/' . $config->watermark);

        if (!file_exists($watermarkPath)) {
            $this->dispatch('swal:error', [
                'title' => false,
                'text' => 'Arquivo de marca d’água não encontrado!',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);
            return;
        }

        $manager = new ImageManager(new Driver());

        $watermark = $manager->read($watermarkPath);

        foreach ($property->images as $image) {

            if ($image->watermark) {
                continue; // pula se já tiver marca
            }

            $imagePath = storage_path('app/public/' . $image->path);

            if (file_exists($imagePath)) {

                $img = $manager->read($imagePath);
                $img->place($watermark, 'bottom-right', 30, 30);
                $img->save($imagePath);

                $image->update([
                    'watermark' => true
                ]);
            }
        }        

        $this->dispatch('swal:success', [
            'title' => false,
            'text' => 'Marca d’água aplicada!',
            'timer' => 2000,
            'showConfirmButton' => false
        ]);        

        $property->refresh();
    }    
}
