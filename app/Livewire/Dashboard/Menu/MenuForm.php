<?php

namespace App\Livewire\Dashboard\Menu;

use App\Models\Menu;
use App\Models\Post;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

class MenuForm extends Component
{
    public ?int $id = null;
    public ?string $title = null;
    public ?int $status = 1;
    public ?int $target = 0;
    public ?string $type = null;
    public ?int $parentId = null;

    public ?int $post = null;
    public ?string $url = null;
    public $pages = [];

    /*
    |--------------------------------------------------------------------------
    | Atualiza quando tipo mudar
    |--------------------------------------------------------------------------
    */
    public function updatedType($value)
    {
        if ($value === 'pagina') {
            $this->pages = Post::where('type', 'pagina')
                ->postson()
                ->orderBy('title')
                ->get();
        } else {
            $this->pages = [];
        }

        $this->post = null;
        $this->url = null;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Carregar menu (edição ou subcategoria)
    |--------------------------------------------------------------------------
    */
    #[On('loadMenu')]
    public function loadMenu($payload = [])
    {
        $this->resetForm();            // 🔥 limpa estado anterior
        $this->resetValidation();  // 🔥 limpa erros

        $data = $payload['payload'] ?? $payload;

        // 🔹 Edição
        if (!empty($data['editId'])) {
            $menu = Menu::find($data['editId']);

            if ($menu) {
                $this->id       = $menu->id;
                $this->title    = $menu->title;
                $this->status   = $menu->status;
                $this->target   = $menu->target;
                $this->type     = $menu->type;
                $this->parentId = $menu->id_pai;

                // 👉 Se for página, carrega primeiro as páginas
                if ($menu->type === 'pagina') {
                    $this->pages = Post::where('type', 'pagina')
                        ->postson()
                        ->orderBy('title')
                        ->get();
                }

                // 👉 Depois define o post_id
                $this->post    = $menu->post;
                $this->url     = $menu->url;
            }
        }

        // 🔹 Nova subcategoria
        if (!empty($data['menuId'])) {
            $this->parentId = $data['menuId'];

            $parent = Menu::find($this->parentId);

            if ($parent) {
                $this->type = $parent->type;
                $this->updatedType($this->type);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    */
    #[On('resetForm')]
    public function resetForm()
    {
        $this->reset([
            'id',
            'title',
            'type',
            'status',
            'target',
            'parentId',
            'post',
            'url'
        ]);

        $this->pages = [];
        $this->status = 1;
    }

    /*
    |--------------------------------------------------------------------------
    | Modal Title
    |--------------------------------------------------------------------------
    */
    public function getModalTitleProperty()
    {
        if ($this->id) return 'Editar Link';
        if ($this->parentId) return 'Cadastrar SubLink';
        return 'Cadastrar Novo Link';
    }

    /*
    |--------------------------------------------------------------------------
    | Save
    |--------------------------------------------------------------------------
    */
    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'type' => Rule::requiredIf($this->parentId === null),
            'status' => 'nullable|integer',

            'post' => $this->type === 'pagina'
                ? 'required|exists:posts,id'
                : 'nullable',

            'url' => $this->type === 'url'
                ? 'required|url'
                : 'nullable',
        ]);

        Menu::updateOrCreate(
            ['id' => $this->id],
            [
                'title'   => $this->title,
                'type'    => $this->type,
                'status' => $this->status ?? 1,
                'target'  => $this->target,
                'id_pai'  => $this->parentId,
                'post'    => $this->type === 'pagina' ? $this->post : null,
                'url'     => $this->type === 'url' ? $this->url : null,
            ]
        );

        $this->resetForm();
        $this->dispatch('menu-saved');
    }

    public function render()
    {
        return view('livewire.dashboard.menu.menu-form');
    }
}
