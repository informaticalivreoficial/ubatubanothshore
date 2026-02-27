<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-file-alt mr-2"></i> {{ $title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <form wire:submit.prevent="update" autocomplete="off">
            <div class="card-body text-muted">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"> 
                        <div class="form-group">
                            <label class="labelforms"><b>Carga Seca/Alimentos:</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                x-data="moneyMask(@entangle('dry_weight'))"
                                x-init="init()"
                                x-bind:value="display"
                                x-on:input="update($event.target.value)"
                                placeholder="R$ 0,00"
                            />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"> 
                        <div class="form-group">
                            <label class="labelforms"><b>Horti-Fruti:</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                x-data="moneyMask(@entangle('horti_fruit'))"
                                x-init="init()"
                                x-bind:value="display"
                                x-on:input="update($event.target.value)"
                                placeholder="R$ 0,00"
                            />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"> 
                        <div class="form-group">
                            <label class="labelforms"><b>Frios/Congelados:</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                x-data="moneyMask(@entangle('glace'))"
                                x-init="init()"
                                x-bind:value="display"
                                x-on:input="update($event.target.value)"
                                placeholder="R$ 0,00"
                            />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"> 
                        <div class="form-group">
                            <label class="labelforms"><b>Carga geral 1.000KG a 5.000KG:</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                x-data="moneyMask(@entangle('general_1000_5000'))"
                                x-init="init()"
                                x-bind:value="display"
                                x-on:input="update($event.target.value)"
                                placeholder="R$ 0,00"
                            />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"> 
                        <div class="form-group">
                            <label class="labelforms"><b>Carga geral acima de 5.000KG:</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                x-data="moneyMask(@entangle('general_above_5000'))"
                                x-init="init()"
                                x-bind:value="display"
                                x-on:input="update($event.target.value)"
                                placeholder="R$ 0,00"
                            />
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"> 
                        <div class="form-group">
                            <label class="labelforms"><b>Metro Cúbico M:</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                x-data="moneyMask(@entangle('cubage'))"
                                x-init="init()"
                                x-bind:value="display"
                                x-on:input="update($event.target.value)"
                                placeholder="R$ 0,00"
                            />
                        </div>
                    </div>                    
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"> 
                        <div class="form-group">
                            <label class="labelforms"><b>Taxas:</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                x-data="moneyMask(@entangle('tax'))"
                                x-init="init()"
                                x-bind:value="display"
                                x-on:input="update($event.target.value)"
                                placeholder="R$ 0,00"
                            />
                        </div>
                    </div>                    
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"> 
                        <div class="form-group">
                            <label class="labelforms"><b>Embalagem:</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                x-data="moneyMask(@entangle('package'))"
                                x-init="init()"
                                x-bind:value="display"
                                x-on:input="update($event.target.value)"
                                placeholder="R$ 0,00"
                            />
                        </div>
                    </div>                    
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3"> 
                        <div class="form-group">
                            <label class="labelforms"><b>Seguro:</b></label>
                            <input 
                                type="text" 
                                class="form-control" 
                                x-data="moneyMask(@entangle('secure'))"
                                x-init="init()"
                                x-bind:value="display"
                                x-on:input="update($event.target.value)"
                                placeholder="R$ 0,00"
                            />
                        </div>
                    </div>                    
                </div>
                <div class="row text-right">
                    <div class="col-12 pb-4">
                        <button type="submit" class="btn btn-lg btn-success p-3"><i class="nav-icon fas fa-check mr-2"></i> <b>Atualizar Agora</b></button>
                    </div>
                </div>
            </div>
        </form>
    </div>    
</div>

<script>
    function moneyMask(modelRef) {
        return {
            model: modelRef,
            display: '',

            init() {
                this.display = this.format(this.model);
            },

            format(value) {
                let number = parseFloat(value);
                if (isNaN(number)) return 'R$ 0,00';

                return number.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
            },

            update(rawValue) {
                let cleaned = rawValue.replace(/\D/g, '');
                let numeric = parseFloat(cleaned) / 100 || 0;

                this.model = numeric; // sincroniza com Livewire
                this.display = this.format(numeric);
            }
        }
    }

    document.addEventListener('atualizado', function() {
        Swal.fire({
            title: 'Sucesso!',
            text: "Manifesto atualizado com sucesso!",
            icon: 'success',
            timerProgressBar: true,
            showConfirmButton: false,
            timer: 3000 // Fecha automaticamente após 3 segundos
        });
    });
</script>
