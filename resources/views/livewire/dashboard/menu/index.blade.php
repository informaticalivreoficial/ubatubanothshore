<div>
    @section('title', $title) 
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-search mr-2"></i> Menus</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Menus</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-12 col-sm-6 my-2">
                    <div class="card-tools">
                        <div style="width: 250px;">
                            <form class="input-group input-group-sm" action="" method="post">
                                <input type="text" wire:model.live="search" class="form-control float-right" placeholder="Pesquisar">               
                                
                            </form>
                        </div>
                      </div>
                </div>
                <div class="col-12 col-sm-6 my-2 text-right">
                    <a @click="$dispatch('open-menu-modal', { editId: null, menuId: null })"
                        class="btn btn-sm btn-default">
                        <i class="fas fa-plus mr-2"></i> 
                        Cadastrar Novo
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if($menus->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped projects">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th class="text-center">Exibir?</th>
                                <th class="text-center">Criado em</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                                <tr style="{{ ($menu->status == true ? '' : 'background: #fffed8 !important;')  }}">
                                    <td><i class="fas fa-angle-right"></i> {{$menu->title}}</td>
                                    <td class="text-center">{{ $menu->status ? 'Sim' : 'Não' }}</td>
                                    <td class="text-center">{{date('d/m/Y', strtotime($menu->created_at))}}</td>
                                    <td class="text-center">{{$menu->type}}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <x-forms.switch-toggle
                                                wire:key="safe-switch-{{ $menu->id }}"
                                                wire:click="toggleStatus({{ $menu->id }})"
                                                :checked="$menu->status"
                                                size="sm"
                                                color="green"
                                            />
                                            <a 
                                                data-id="{{ $menu->id }}"
                                                x-on:click="$dispatch('open-menu-modal', { editId: parseInt($el.dataset.id) })"
                                                class="btn btn-xs btn-default">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            
                                            <a 
                                                data-parent-id="{{ $menu->id }}"
                                                x-on:click="$dispatch('open-menu-modal', { menuId: parseInt($el.dataset.parentId) })" 
                                            class="btn btn-sm btn-success">
                                            Criar SubLink
                                            </a>  

                                            <button type="button" 
                                                class="btn btn-xs bg-danger text-white" 
                                                title="Excluir Link"
                                                wire:click="setDeleteId({{ $menu->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>                                                                         
                                    </td>
                                </tr>
                                @if ($menu->children()->count() > 0)
                                    @foreach($menu->children()->get() as $submenu)                        
                                    <tr style="{{ ($submenu->status == true ? '' : 'background: #fffed8 !important;')  }}">                            
                                        <td><i class="fas fa-angle-double-right"></i>  {{$submenu->title}}</td>
                                        <td class="text-center">{{ $submenu->status ? 'Sim' : 'Não' }}</td>
                                        <td class="text-center">{{date('d/m/Y', strtotime($submenu->created_at))}}</td>
                                        <td class="text-center">---------</td>
                                        <td>   
                                            <div class="flex items-center gap-2">
                                                <x-forms.switch-toggle
                                                    wire:key="safe-switch-{{ $submenu->id }}"
                                                    wire:click="toggleStatus({{ $submenu->id }})"
                                                    :checked="$submenu->status"
                                                    size="sm"
                                                    color="green"
                                                />
                                                <a 
                                                    data-edit-id="{{ $submenu->id }}"
                                                    x-on:click="$dispatch('open-menu-modal', { editId: parseInt($el.dataset.editId) })" 
                                                    class="btn btn-xs btn-default"><i class="fas fa-pen"></i>
                                                </a>
                                                <button 
                                                    type="button" 
                                                    class="btn btn-xs bg-danger text-white" 
                                                    title="Excluir SubLink"
                                                    wire:click="setDeleteId({{ $submenu->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table> 
                </div>               
                <div class="mt-3">
                    {{$menus->links()}}
                </div>         
            @else
                <div class="alert alert-info mb-0">
                    Nenhum registro encontrado!
                </div>
            @endif 
            
            <div 
                x-data="{ open: false }"
                x-on:open-menu-modal.window="
                    open = true;
                    Livewire.dispatch('loadMenu', { payload: $event.detail })
                "
                x-on:menu-saved.window="open = false"
                x-show="open"
                style="display: none"
                class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-[1050]"
            >
                <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-2">
                    <livewire:dashboard.menu.menu-form />                    
                </div>
            </div>

            
        </div>
    </div>

   
</div>

