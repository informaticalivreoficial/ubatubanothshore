<?php

namespace App\Livewire\Components;

use Livewire\Attributes\On;
use Livewire\Component;

class ConfirmDelete extends Component
{
    public $model;
    public $deleteId;

    public function setDeleteId($id)
    {
        $this->deleteId = $id;
        $this->dispatch('delete-prompt');        
    }

    #[On('goOn-Delete')]
    public function delete()
    {
        $model = $this->model::find($this->deleteId); 

        if ($model) {
            $model->delete();
            $this->deleteId = null;

            $this->dispatch('swal', [
                'title' => 'Success!',
                'icon' => 'success',
                'text' => 'Registro deletado com sucesso!'
            ]);
        }else {
            $this->dispatch('swal', [
                'title' => 'Error!',
                'icon' => 'error',
                'text' => 'Viagem não encontrada!'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.components.confirm-delete');
    }
}
