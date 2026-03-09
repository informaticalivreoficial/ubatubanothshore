<div>
    @section('title', $titlee)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-home mr-2"></i> {{ $property->exists ? 'Editar Imóvel' : 'Cadastrar Imóvel' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{route('properties.index')}}">Imóveis</a></li>
                        <li class="breadcrumb-item active">{{ $property->exists ? 'Editar' : 'Cadastrar' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{
        tab: @entangle('currentTab'),
            init() {
                if (!this.tab) this.tab = 'dados';
            }
        }" class="w-full bg-white">
        <!-- Abas -->
        <div class="flex space-x-2 border-b border-green-300">
            <button type="button"
                    class="px-4 py-4 text-sm font-medium rounded-t-lg focus:outline-none transition-all duration-200"
                    :class="tab === 'dados' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-500'"
                    @click="tab = 'dados'">
                📝 Dados
            </button>
            <button type="button"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg focus:outline-none transition-all duration-200"
                    :class="tab === 'estrutura' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-500'"
                    @click="tab = 'estrutura'">
                🏗️ Estrutura
            </button>
            <button type="button"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg focus:outline-none transition-all duration-200"
                    :class="tab === 'imagens' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-500'"
                    @click="tab = 'imagens'">
                📷 Imagens
            </button>
            <button type="button"
                    class="px-4 py-2 text-sm font-medium rounded-t-lg focus:outline-none transition-all duration-200"
                    :class="tab === 'seo' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-500'"
                    @click="tab = 'seo'">
                🔍 Seo
            </button>
            @if ($property->exists)
                <button type="button"
                        class="px-4 py-2 text-sm font-medium rounded-t-lg focus:outline-none transition-all duration-200"
                        :class="tab === 'season' ? 'bg-white border-l border-t border-r text-blue-600' : 'text-gray-500 hover:text-blue-500'"
                        @click="tab = 'season'">
                    📅 Temporada
                </button>
            @endif            
        </div>
        
            <!-- Conteúdo da aba Dados -->
            <div x-show="tab === 'dados'" x-transition>
                <div class="bg-white">
                    <div class="card-body text-muted">
                        <div class="row">                           
                            <div class="col-12 col-md-6 col-lg-5">   
                                <div class="form-group">
                                    <label class="labelforms"><b>*Título</b></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"  wire:model="title">
                                    @error('title')
                                        <span class="error erro-feedback">{{ $message }}</span>
                                    @enderror
                                </div>                                                    
                            </div>   
                            <div class="col-12 col-md-3 col-lg-2"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>Referência</b></label>
                                    <input type="text" class="form-control" wire:model="reference">
                                </div>
                            </div> 
                            <div class="col-12 col-md-3 col-lg-2">
                                <div class="form-group" x-data="{ value: @entangle('expired_at').defer }" x-init="initFlatpickr()" x-ref="datepicker">
                                    <label class="labelforms"><b>Data de Expiração</b></label>
                                    <input type="text" class="form-control" wire:model="expired_at" id="datepicker" />                                                                                                                                                                          
                                </div>
                            </div> 
                            <div class="col-12 col-md-6 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>*Categoria</b></label>
                                    <select class="form-control @error('category') is-invalid @enderror" wire:model="category">
                                        <option value=""> Selecione </option>
                                        <option value="Imóvel Residencial">Imóvel Residencial</option>
                                        <option value="Comercial/Industrial">Comercial/Industrial</option>
                                        <option value="Terreno">Terreno</option>
                                        <option value="Rural">Rural</option>
                                    </select>
                                    @error('category')
                                        <span class="error erro-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>               
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>*Tipo</b></label>
                                    <select class="form-control @error('type') is-invalid @enderror" wire:model="type">
                                        <option value=""> Selecione </option>
                                        <option value="Casa">Casa</option>
                                        <option value="Cobertura">Cobertura</option>
                                        <option value="Apartamento">Apartamento</option>
                                        <option value="Studio">Studio</option>
                                        <option value="Kitnet">Kitnet</option>
                                        <option value="Sala Comercial">Sala Comercial</option>
                                        <option value="Salão de Festa">Salão de Festa</option>
                                        <option value="Chalé">Chalé</option>
                                        <option value="Hotel Pousada">Hotel/Pousada</option>
                                        <option value="Sítio">Sítio</option>
                                        <option value="Sobrado">Sobrado</option>
                                        <option value="Loja">Loja</option>
                                        <option value="Terreno em Condomínio">Terreno em Condomínio</option>
                                        <option value="Terreno">Terreno</option>
                                        <option value="Fazenda">Fazenda</option>
                                        <option value="Prédio Edifício Inteiro">Prédio/Edifício Inteiro</option>
                                    </select>
                                    @error('type')
                                        <span class="error erro-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>Max. Hóspedes</b></label>
                                    <input type="text" class="form-control" wire:model="capacity">
                                </div>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>Max. Hóspedes Extras</b></label>
                                    <input type="text" class="form-control" wire:model="aditional_person">
                                </div>
                            </div>
                            <div class="col-12 col-md-3 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>Mínimo de diárias</b></label>
                                    <input type="text" class="form-control" wire:model="min_nights">
                                </div>
                            </div>
                        </div>
                        <hr class="my-4 border-gray-300">
                        <div class="row">
                            <div class="col-12"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted">
                                        <b>Deseja exibir os valores?</b>
                                        <small class="text-info">(valores exibidos no layout do cliente)</small>
                                    </label>
                                    <div class="form-check">
                                        <input id="display_valuessim" class="form-check-input" type="radio" value="1" wire:model="display_values">
                                        <label for="display_valuessim" class="form-check-label mr-5">Sim</label>
                                        <input id="display_valuesnao" class="form-check-input" type="radio" value="0" wire:model="display_values">
                                        <label for="display_valuesnao" class="form-check-label">Não</label>
                                    </div>
                                </div>
                            </div>

                            {{-- Campo: Valor da Locação --}}
                            <div class="col-12 col-md-3 col-lg-3"> 
                                <div class="form-group"
                                    x-data="{
                                        display: '',
                                        init() {
                                            // Inicializa com o valor do Livewire, se existir
                                            let v = '{{ $rental_value ?? '' }}';
                                            if (v) this.display = this.toDisplay(parseFloat(v));
                                        },
                                        toDisplay(num) {
                                            if (isNaN(num)) return '';
                                            return num.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                        },
                                        toRaw(str) {
                                            // '1.200,50' → '1200.50'
                                            return str.replace(/\./g, '').replace(',', '.');
                                        },
                                        onInput(e) {
                                            let raw = e.target.value.replace(/\D/g, '');
                                            if (!raw) { this.display = ''; this.$wire.set('rental_value', null); return; }
                                            let num = parseInt(raw, 10) / 100;
                                            this.display = this.toDisplay(num);
                                            this.$wire.set('rental_value', num.toFixed(2));
                                        }
                                    }"
                                >
                                    <label class="labelforms text-muted"><b>Valor da Locação</b></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        :value="display"
                                        @input="onInput($event)"
                                        placeholder="Ex: 1.200,50"
                                        inputmode="numeric"
                                    />
                                </div>
                            </div>

                            {{-- Campo: Valor Hóspede Adicional --}}
                            <div class="col-12 col-md-3 col-lg-3"> 
                                <div class="form-group"
                                    x-data="{
                                        display: '',
                                        init() {
                                            let v = '{{ $value_aditional ?? '' }}';
                                            if (v) this.display = this.toDisplay(parseFloat(v));
                                        },
                                        toDisplay(num) {
                                            if (isNaN(num)) return '';
                                            return num.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                        },
                                        onInput(e) {
                                            let raw = e.target.value.replace(/\D/g, '');
                                            if (!raw) { this.display = ''; this.$wire.set('value_aditional', null); return; }
                                            let num = parseInt(raw, 10) / 100;
                                            this.display = this.toDisplay(num);
                                            this.$wire.set('value_aditional', num.toFixed(2));
                                        }
                                    }"
                                >
                                    <label class="labelforms text-muted"><b>Valor Hóspede Adicional</b></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        :value="display"
                                        @input="onInput($event)"
                                        placeholder="Ex: 200,00"
                                        inputmode="numeric"
                                    />
                                </div>
                            </div>

                            {{-- Campo: Taxa de Limpeza --}}
                            <div class="col-12 col-md-3 col-lg-3"> 
                                <div class="form-group"
                                    x-data="{
                                        display: '',
                                        init() {
                                            let v = '{{ $cleaning_fee ?? '' }}';
                                            if (v) this.display = this.toDisplay(parseFloat(v));
                                        },
                                        toDisplay(num) {
                                            if (isNaN(num)) return '';
                                            return num.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                        },
                                        onInput(e) {
                                            let raw = e.target.value.replace(/\D/g, '');
                                            if (!raw) { this.display = ''; this.$wire.set('cleaning_fee', null); return; }
                                            let num = parseInt(raw, 10) / 100;
                                            this.display = this.toDisplay(num);
                                            this.$wire.set('cleaning_fee', num.toFixed(2));
                                        }
                                    }"
                                >
                                    <label class="labelforms text-muted"><b>Taxa de Limpeza</b></label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        :value="display"
                                        @input="onInput($event)"
                                        placeholder="Ex: 150,00"
                                        inputmode="numeric"
                                    />
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 border-gray-300">
                        <div class="row mb-2">
                            <div class="col-12 mb-2"> 
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Deseja exibir o endereço? <small class="text-info">(opção não exibir retornará somente a cidade e estado)</small></b></label>
                                    <div class="form-check">
                                        <input id="display_addresssim" class="form-check-input" type="radio" value="1" wire:model="display_address">
                                        <label for="display_addresssim" class="form-check-label mr-5">Sim</label>
                                        <input id="display_addressnao" class="form-check-input" type="radio" value="0" wire:model="display_address">
                                        <label for="display_addressnao" class="form-check-label">Não</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>*CEP:</b></label>
                                    <input type="text" x-mask="99.999-999" class="form-control @error('zipcode') is-invalid @enderror" id="zipcode" wire:model.lazy="zipcode">
                                    @error('zipcode')
                                        <span class="error erro-feedback">{{ $message }}</span>
                                    @enderror                                                    
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-4 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>*Estado:</b></label>
                                    <input type="text" class="form-control" id="state" wire:model="state" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 col-lg-4"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>*Cidade:</b></label>
                                    <input type="text" class="form-control" id="city" wire:model="city" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>*Rua:</b></label>
                                    <input type="text" class="form-control" id="street" wire:model="street" readonly>
                                </div>
                            </div>                                            
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 col-md-4 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>*Bairro:</b></label>
                                    <input type="text" class="form-control" id="neighborhood" wire:model="neighborhood" readonly>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-2"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>Número:</b></label>
                                    <input type="text" class="form-control" id="number" wire:model="number">
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3"> 
                                <div class="form-group">
                                    <label class="labelforms"><b>Complemento:</b></label>
                                    <input type="text" class="form-control" id="complement" wire:model="complement">
                                </div>
                            </div>   
                        </div>

                        <hr class="my-4 border-gray-300">

                        <div class="row mb-2">
                            <div class="col-12 col-md-6 col-lg-2">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>*Dormitórios</b></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model="dormitories">
                                    @error('dormitories')
                                        <span class="error erro-feedback">{{ $message }}</span>
                                    @enderror
                                </div>                                                    
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Suítes</b></label>
                                    <input type="text" class="form-control" wire:model="suites">
                                </div>                                                    
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Banheiros</b></label>
                                    <input type="text" class="form-control" wire:model="bathrooms">
                                </div>                                                    
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Salas</b></label>
                                    <input type="text" class="form-control" wire:model="rooms">
                                </div>                                                    
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Garagem</b></label>
                                    <input type="text" class="form-control" wire:model="garage">
                                </div>                                                    
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Garagem Coberta</b></label>
                                    <input type="text" class="form-control" wire:model="covered_garage">
                                </div>                                                    
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Ano de Construção</b></label>
                                    <input type="text" class="form-control" wire:model="construction_year">
                                </div>                                                    
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Área Total</b></label>
                                    <input type="text" class="form-control" wire:model="total_area">
                                </div>                                                    
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Área Útil</b></label>
                                    <input type="text" class="form-control" wire:model="useful_area">
                                </div>                                                    
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Medidas</b></label>
                                    <select class="form-control" wire:model="measures">
                                        <option value=""> Selecione </option>
                                        <option value="m²">m²</option>
                                        <option value="km²">km²</option>
                                        <option value="hectare">hectare</option>
                                        <option value="alqueire">alqueire</option>
                                    </select>
                                </div>                                                    
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Latitude</b></label>
                                    <input type="text" class="form-control" wire:model="latitude">
                                </div>                                                    
                            </div>
                            <div class="col-12 col-md-6 col-lg-2">   
                                <div class="form-group">
                                    <label class="labelforms text-muted"><b>Longitude</b></label>
                                    <input type="text" class="form-control" wire:model="longitude">
                                </div>                                                    
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12" wire:ignore>   
                                <label class="labelforms text-muted"><b>Descrição do Imóvel</b></label>
                                <textarea id="description" wire:model="description">{{ $description ?? '' }}</textarea>                                                                                     
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12">   
                                <label class="labelforms text-muted"><b>Notas Adicionais</b></label>
                                <textarea id="inputDescription" class="form-control" rows="5" wire:model="additional_notes">{{ $additional_notes ?? 'Os valores podem ser alterados sem aviso prévio. Informações e metragens sujeitos a confirmações.'}}</textarea>                                                      
                            </div>
                        </div>  
                        <div class="row mb-2">
                            <div class="col-12">   
                                <label class="labelforms text-muted"><b>Política de cancelamento</b></label>
                                <textarea id="inputDescription" class="form-control" rows="5" wire:model="politica_cancelamento">
                                    {{ $politica_cancelamento ?? "100% reembolso até 30 dias antes chegada.\n50% reembolso até 14 dias antes chegada."}}
                                </textarea>                                                      
                            </div>
                        </div> 

                        @if ($property->exists)
                            <div class="row mb-2">
                                <div class="col-12">
                                    <livewire:dashboard.properties.calendar :property="$property" />
                                </div>
                            </div>
                        @endif                       
                        
                        
                                                
                    </div>
                </div>
            </div>


            <div x-show="tab === 'estrutura'" x-transition>
                <div class="bg-white p-4">
                    <div class="row mb-4">

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group p-3 mb-1">

                                <div class="form-check mb-2">
                                    <input id="acesso_praia" class="form-check-input" type="checkbox" wire:model="acesso_praia">
                                    <label for="acesso_praia" class="form-check-label">Acesso à Praia</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="adequado_criancas" class="form-check-input" type="checkbox" wire:model="adequado_criancas">
                                    <label for="adequado_criancas" class="form-check-label">Adequado para Crianças</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="adequado_idosos" class="form-check-input" type="checkbox" wire:model="adequado_idosos">
                                    <label for="adequado_idosos" class="form-check-label">Adequado para Idosos</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="agua_quente" class="form-check-input" type="checkbox" wire:model="agua_quente">
                                    <label for="agua_quente" class="form-check-label">Água Quente</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="aquecedor" class="form-check-input" type="checkbox" wire:model="aquecedor">
                                    <label for="aquecedor" class="form-check-label">Aquecedor</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="ar_condicionado" class="form-check-input" type="checkbox" wire:model="ar_condicionado">
                                    <label for="ar_condicionado" class="form-check-label">Ar Condicionado</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="areadelazer" class="form-check-input" type="checkbox" wire:model="areadelazer">
                                    <label for="areadelazer" class="form-check-label">Área de Lazer</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="banheira" class="form-check-input" type="checkbox" wire:model="banheira">
                                    <label for="banheira" class="form-check-label">Banheira</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="banheiro_privativo" class="form-check-input" type="checkbox" wire:model="banheiro_privativo">
                                    <label for="banheiro_privativo" class="form-check-label">Banheiro Privativo</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="cafeteira" class="form-check-input" type="checkbox" wire:model="cafeteira">
                                    <label for="cafeteira" class="form-check-label">Cafeteira</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="cama_casal" class="form-check-input" type="checkbox" wire:model="cama_casal">
                                    <label for="cama_casal" class="form-check-label">Cama de Casal</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="cameras" class="form-check-input" type="checkbox" wire:model="cameras">
                                    <label for="cameras" class="form-check-label">Câmeras</label>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group p-3 mb-1">

                                <div class="form-check mb-2">
                                    <input id="churrasqueira" class="form-check-input" type="checkbox" wire:model="churrasqueira">
                                    <label for="churrasqueira" class="form-check-label">Churrasqueira</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="condominiofechado" class="form-check-input" type="checkbox" wire:model="condominiofechado">
                                    <label for="condominiofechado" class="form-check-label">Condomínio Fechado</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="cozinha" class="form-check-input" type="checkbox" wire:model="cozinha">
                                    <label for="cozinha" class="form-check-label">Cozinha</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="elevador" class="form-check-input" type="checkbox" wire:model="elevador">
                                    <label for="elevador" class="form-check-label">Elevador</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="espaco_fitness" class="form-check-input" type="checkbox" wire:model="espaco_fitness">
                                    <label for="espaco_fitness" class="form-check-label">Espaço Fitness</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="estacionamento" class="form-check-input" type="checkbox" wire:model="estacionamento">
                                    <label for="estacionamento" class="form-check-label">Estacionamento</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="fornopizza" class="form-check-input" type="checkbox" wire:model="fornopizza">
                                    <label for="fornopizza" class="form-check-label">Forno de Pizza</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="frigobar" class="form-check-input" type="checkbox" wire:model="frigobar">
                                    <label for="frigobar" class="form-check-label">Frigobar</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="garagem" class="form-check-input" type="checkbox" wire:model="garagem">
                                    <label for="garagem" class="form-check-label">Garagem</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="geladeira" class="form-check-input" type="checkbox" wire:model="geladeira">
                                    <label for="geladeira" class="form-check-label">Geladeira</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="interfone" class="form-check-input" type="checkbox" wire:model="interfone">
                                    <label for="interfone" class="form-check-label">Interfone</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="jardim" class="form-check-input" type="checkbox" wire:model="jardim">
                                    <label for="jardim" class="form-check-label">Jardim</label>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group p-3 mb-1">

                                <div class="form-check mb-2">
                                    <input id="lareira" class="form-check-input" type="checkbox" wire:model="lareira">
                                    <label for="lareira" class="form-check-label">Lareira</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="lavabo" class="form-check-input" type="checkbox" wire:model="lavabo">
                                    <label for="lavabo" class="form-check-label">Lavabo</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="maquina_lavar" class="form-check-input" type="checkbox" wire:model="maquina_lavar">
                                    <label for="maquina_lavar" class="form-check-label">Máquina de Lavar</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="mesa_refeicao" class="form-check-input" type="checkbox" wire:model="mesa_refeicao">
                                    <label for="mesa_refeicao" class="form-check-label">Mesa de Refeição</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="mesa_trabalho" class="form-check-input" type="checkbox" wire:model="mesa_trabalho">
                                    <label for="mesa_trabalho" class="form-check-label">Mesa de Trabalho</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="microondas" class="form-check-input" type="checkbox" wire:model="microondas">
                                    <label for="microondas" class="form-check-label">Microondas</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="mobiliado" class="form-check-input" type="checkbox" wire:model="mobiliado">
                                    <label for="mobiliado" class="form-check-label">Mobiliado</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="permiteanimais" class="form-check-input" type="checkbox" wire:model="permiteanimais">
                                    <label for="permiteanimais" class="form-check-label">Permite Animais</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="piscina" class="form-check-input" type="checkbox" wire:model="piscina">
                                    <label for="piscina" class="form-check-label">Piscina</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="portaria24hs" class="form-check-input" type="checkbox" wire:model="portaria24hs">
                                    <label for="portaria24hs" class="form-check-label">Portaria 24h</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="pratos_talheres" class="form-check-input" type="checkbox" wire:model="pratos_talheres">
                                    <label for="pratos_talheres" class="form-check-label">Pratos e Talheres</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="produtos_limpeza" class="form-check-input" type="checkbox" wire:model="produtos_limpeza">
                                    <label for="produtos_limpeza" class="form-check-label">Produtos de Limpeza</label>
                                </div>

                            </div>
                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                            <div class="form-group p-3 mb-1">

                                <div class="form-check mb-2">
                                    <input id="roupa_cama" class="form-check-input" type="checkbox" wire:model="roupa_cama">
                                    <label for="roupa_cama" class="form-check-label">Roupa de Cama</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="sauna" class="form-check-input" type="checkbox" wire:model="sauna">
                                    <label for="sauna" class="form-check-label">Sauna</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="salaodejogos" class="form-check-input" type="checkbox" wire:model="salaodejogos">
                                    <label for="salaodejogos" class="form-check-label">Salão de Jogos</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="secador_cabelo" class="form-check-input" type="checkbox" wire:model="secador_cabelo">
                                    <label for="secador_cabelo" class="form-check-label">Secador de Cabelo</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="secadora" class="form-check-input" type="checkbox" wire:model="secadora">
                                    <label for="secadora" class="form-check-label">Secadora</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="tv" class="form-check-input" type="checkbox" wire:model="tv">
                                    <label for="tv" class="form-check-label">TV</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="tv_netflix" class="form-check-input" type="checkbox" wire:model="tv_netflix">
                                    <label for="tv_netflix" class="form-check-label">TV com Netflix</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="ventilador_teto" class="form-check-input" type="checkbox" wire:model="ventilador_teto">
                                    <label for="ventilador_teto" class="form-check-label">Ventilador de Teto</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="vista_para_mar" class="form-check-input" type="checkbox" wire:model="vista_para_mar">
                                    <label for="vista_para_mar" class="form-check-label">Vista para o Mar</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input id="wifi" class="form-check-input" type="checkbox" wire:model="wifi">
                                    <label for="wifi" class="form-check-label">Wi-Fi</label>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        
            <div x-show="tab === 'imagens'" x-transition class="relative">

                <div
                    wire:loading
                    wire:target="images"
                    class="absolute inset-0 bg-white/80 flex items-center justify-center z-[10000]"
                >
                    <div class="flex flex-col items-center gap-2">
                        <svg class="animate-spin h-8 w-8 text-blue-600"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>

                        <span class="text-sm text-gray-700 font-medium">
                            Carregando imagens...
                        </span>
                    </div>
                </div>

                <div class="bg-white p-4">
                    <div class="row">                        
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">   
                            <div class="form-group text-muted">
                                <label class="labelforms"><b>Legenda da Imagem de Capa</b></label>
                                <input type="text" class="form-control"  wire:model="caption_img_cover">
                            </div>                                                    
                        </div>
                    </div>

                    <hr class="my-4 border-gray-300">

                    <label class="block font-semibold mb-2 mt-2 text-muted">📁 Upload de Imagens:</label>
                    <input type="file" wire:model="images" class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0 file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" multiple/>

                    @error('images')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Informação sobre ordenação -->
                        @if(count($property->images ?? []) > 1)
                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
                                <p class="text-sm text-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <strong>Dica:</strong> Arraste e solte as imagens para reordená-las. A ordem será salva automaticamente.
                                </p>
                            </div>
                        @endif

                    
                    <div x-data="imageGallery()">
                        <!-- Galeria de Imagens com Drag & Drop -->
                        <div class="flex flex-wrap gap-4 mt-4" id="sortable-gallery">
                            {{-- Imagens já salvas (vindas do banco) --}}
                            @foreach ($property->images ?? [] as $savedImage)
                                <div 
                                    class="relative image-item cursor-move"
                                    data-id="{{ $savedImage->id }}"
                                    draggable="true"
                                    @dragstart="dragStart($event)"
                                    @dragover.prevent="dragOver($event)"
                                    @drop="drop($event)"
                                    @dragend="dragEnd($event)"
                                >
                                    <img src="{{ Storage::url($savedImage->path) }}"
                                        class="w-32 h-32 object-cover rounded border cursor-pointer transition-transform hover:scale-105
                                                {{ $savedImage->cover ? 'ring-4 ring-green-500' : '' }}"
                                        @click="showModal = true; imageUrl = '{{ Storage::url($savedImage->path) }}'">

                                    {{-- Indicador de drag --}}
                                    <div class="absolute top-1 left-1 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        </svg>
                                    </div>

                                    {{-- Número da ordem --}}
                                    <div class="absolute top-1 left-10 bg-blue-600 text-white text-xs px-2 py-1 rounded font-bold">
                                        {{ $loop->index + 1 }}
                                    </div>

                                    {{-- Botão de excluir --}}
                                    <button type="button"
                                            wire:click="removeSavedImage({{ $savedImage->id }})"
                                            class="absolute top-1 right-1 w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full text-xs hover:bg-red-600">
                                        ✕
                                    </button>

                                    {{-- Botão de definir/remover capa --}}
                                    <button type="button"
                                            wire:click="toggleCover({{ $savedImage->id }})"
                                            class="absolute bottom-1 left-1 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded hover:bg-black">
                                        {{ $savedImage->cover ? 'Remover capa' : 'Definir capa' }}
                                    </button>

                                    @if (!$savedImage->watermark)
                                        <button type="button" title="Inserir Marca d'água"
                                            wire:click="applyWatermarkImage({{ $savedImage->id }})"
                                            class="absolute bottom-1 right-1 bg-yellow-500 px-2 py-1 rounded">
                                            <i class="fas fa-copyright"></i>
                                        </button>
                                    @endif                                    
                                </div>
                            @endforeach

                            {{-- Imagens recém-uploadadas via Livewire --}}
                            @foreach ($images as $index => $image)
                                <div class="relative">
                                    <img src="{!! $image->temporaryUrl() !!}" class="w-32 h-32 object-cover rounded border cursor-pointer opacity-70"
                                        @click="showModal = true; imageUrl = '{!! $image->temporaryUrl() !!}'">
                                    
                                    {{-- Badge de nova imagem --}}
                                    <div class="absolute top-1 left-1 bg-yellow-500 text-white text-xs px-2 py-1 rounded font-bold">
                                        NOVA
                                    </div>
                                    
                                    <button type="button"
                                            wire:click="removeTempImage({{ $index }})"
                                            class="absolute top-1 right-1 w-6 h-6 flex items-center justify-center bg-red-500 text-white rounded-full text-xs hover:bg-red-600">
                                        ✕
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <!-- Modal de imagem -->
                        <div x-show="showModal" x-cloak
                            class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-[9999]"
                            x-transition
                            @click.self="showModal = false">
                            <div class="relative">
                                <img :src="imageUrl" class="max-w-[70vw] max-h-[70vh] object-contain mx-auto rounded shadow-lg">
                                <button type="button" @click="showModal = false"
                                        class="absolute top-2 right-2 text-white text-xl bg-black bg-opacity-50 rounded-full px-3 py-1 hover:bg-opacity-75">
                                    ✕
                                </button>
                            </div>
                        </div>
                    </div>


                </div>
            </div>


            <div x-show="tab === 'seo'" x-transition>
                <div class="bg-white p-4">
                    <div class="row mb-2 text-muted">                                   
                        <div class="col-12 col-md-6 col-lg-6">   
                            <div class="form-group">
                                <label class="labelforms"><b>Headline</b></label>
                                <input type="text" class="form-control" wire:model="headline">
                            </div>                                                    
                        </div>
                        <div class="col-12 col-md-6 col-lg-6"> 
                            <div class="form-group">
                                <label class="labelforms"><b>Experiência</b></label>
                                <select class="form-control" wire:model="experience">
                                    <option value=""> Selecione </option>
                                    <option value="Cobertura">Cobertura</option>
                                    <option value="Alto Padrão">Alto Padrão</option>
                                    <option value="De Frente para o Mar">De Frente para o Mar</option>
                                    <option value="Condomínio Fechado">Condomínio Fechado</option>
                                    <option value="Compacto">Compacto</option>
                                    <option value="Lojas e Salas">Lojas e Salas</option>
                                </select>
                            </div>
                        </div>                                    
                        <div class="col-12 mb-1"> 
                            <div class="form-group">
                                <label class="labelforms"><b>MetaTags</b></label>
                                <div 
                                    x-data="{
                                        tags: @entangle('metatags'),
                                        input: '',
                                        addTag() {
                                            const trimmed = this.input.trim();
                                            if (trimmed && !this.tags.includes(trimmed)) {
                                                this.tags.push(trimmed);
                                            }
                                            this.input = '';
                                        },
                                        removeTag(index) {
                                            this.tags.splice(index, 1);
                                        }
                                    }"
                                    class="p-4 border rounded shadow"
                                    >
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        <template x-for="(tag, index) in tags" :key="index">
                                            <span class="flex items-center bg-teal-500 text-white px-3 py-1 rounded-full">
                                                <span x-text="tag"></span>
                                                <button type="button" @click="removeTag(index)" class="ml-2 hover:text-gray-200">&times;</button>
                                            </span>
                                        </template>
                                    </div>                                    
                                    <input 
                                        type="text" 
                                        x-model="input" 
                                        @keydown.enter.prevent="addTag"
                                        placeholder="Digite uma tag e pressione Enter"
                                        class="border border-gray-300 rounded px-3 py-2 w-full"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="col-12"> 
                            <div class="form-group">
                                <label class="labelforms"><b>Youtube Vídeo</b></label>
                                <textarea id="inputDescription" class="form-control" rows="5" wire:model="youtube_video"></textarea> 
                            </div>
                        </div> 
                        <div class="col-12">   
                            <label class="labelforms"><b>Mapa do Google</b> <small class="text-info">(Copie o código de incorporação do Google Maps e cole abaixo)</small></label>
                            <textarea id="inputDescription" class="form-control" rows="5" wire:model="google_map"></textarea>                                                      
                        </div>
                    </div> 
                </div>
            </div>

            @if ($property->exists)
                <div x-show="tab === 'season'" x-transition>
                    <div class="bg-white p-4">
                        <div class="card-body text-muted">
                            <div class="row">                           
                                <div class="col-12">
                                    <livewire:dashboard.properties.seasons :property="$property" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif             

            <div class="row text-right p-4 bg-white">
                <div class="col-12 mb-4">
                    <button 
                        wire:loading.attr="disabled"
                        wire:target="images"
                        type="button" 
                        wire:click="save('draft')" class="btn btn-info"><i class="nav-icon fas fa-check mr-2"></i>{{ $property->exists ? 'Atualizar Rascunho' : 'Salvar Rascunho' }}</button>
                    <button 
                        wire:loading.attr="disabled"
                        wire:target="images"
                        type="button" 
                        wire:click="save('published')" 
                    class="btn btn-success"><i class="nav-icon fas fa-check mr-2"></i>{{ $property->exists ? 'Atualizar e Publicar' : 'Salvar e Publicar' }}</button>
                </div>
            </div>
       
    </div>
</div>

<script>    
    
    document.addEventListener("livewire:navigated", () => {
        $('#description').summernote({
            height: 300,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']]
            ],
            callbacks: {
                onChange: function(contents, $editable) {
                    Livewire.dispatch('updateDescription', { value: contents });
                }
            }
        });
    });

    function tagInputComponent(tagsBinding) {
        return {
            tags: tagsBinding,
            input: '',
            addTag() {
                const trimmed = this.input.trim();
                if (trimmed && !this.tags.includes(trimmed)) {
                    this.tags.push(trimmed);
                }
                this.input = '';
            },
            removeTag(index) {
                this.tags.splice(index, 1);
            }
        };
    }

    function initFlatpickr() {
        let input = document.getElementById('datepicker');
        if (!input) return;

        flatpickr(input, {
            dateFormat: "d/m/Y",
            allowInput: true,
            minDate: "today",
            //defaultDate: input.value, // Carrega a data inicial corretamente
            onChange: function(selectedDates, dateStr) {
                input.dispatchEvent(new Event('input')); // Força atualização no Alpine.js
            },
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
                    longhand: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'],
                },
                months: {
                    shorthand: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    longhand: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                },
                today: "Hoje",
                clear: "Limpar",
                weekAbbreviation: "Sem",
                scrollTitle: "Role para aumentar",
                toggleTitle: "Clique para alternar",
            }
        });
    }

    document.addEventListener("livewire:load", () => {
        initFlatpickr();
    });

    document.addEventListener("livewire:updated", () => {
        initFlatpickr();
    });

    
    function imageGallery() {
        return {
            showModal: false,
            imageUrl: null,
            draggedElement: null,
            
            dragStart(e) {
                this.draggedElement = e.target.closest('.image-item');
                this.draggedElement.classList.add('opacity-50', 'scale-95');
                e.dataTransfer.effectAllowed = 'move';
            },
            
            dragOver(e) {
                e.preventDefault();
                const container = e.currentTarget.parentElement;
                const afterElement = this.getDragAfterElement(container, e.clientX, e.clientY);
                const currentElement = e.currentTarget.closest('.image-item');
                
                if (afterElement == null) {
                    container.appendChild(this.draggedElement);
                } else {
                    container.insertBefore(this.draggedElement, afterElement);
                }
            },
            
            drop(e) {
                e.preventDefault();
                this.updateOrder();
            },
            
            dragEnd(e) {
                this.draggedElement.classList.remove('opacity-50', 'scale-95');
                this.draggedElement = null;
            },
            
            getDragAfterElement(container, x, y) {
                const draggableElements = [...container.querySelectorAll('.image-item:not(.opacity-50)')];
                
                return draggableElements.reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offsetX = x - box.left - box.width / 2;
                    const offsetY = y - box.top - box.height / 2;
                    const offset = Math.sqrt(offsetX * offsetX + offsetY * offsetY);
                    
                    if (offset < closest.offset && offsetX < 0) {
                        return { offset: offset, element: child };
                    } else {
                        return closest;
                    }
                }, { offset: Number.POSITIVE_INFINITY }).element;
            },
            
            updateOrder() {
                const gallery = document.getElementById('sortable-gallery');
                const imageItems = gallery.querySelectorAll('.image-item');
                const order = [];
                
                imageItems.forEach((item, index) => {
                    const id = item.getAttribute('data-id');
                    order.push({ id: parseInt(id), position: index + 1 });
                });
                
                // Envia a nova ordem para o Livewire
                @this.call('updateImageOrder', order);
                
                // Feedback visual
                this.showSuccessMessage();
            },
            
            showSuccessMessage() {
                const message = document.createElement('div');
                message.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50 transition-opacity';
                message.innerHTML = '✓ Ordem das imagens atualizada!';
                document.body.appendChild(message);
                
                setTimeout(() => {
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 300);
                }, 2000);
            }
        }
    }
</script>

@push('styles')
    <style>
        .image-item {
            transition: transform 0.2s, opacity 0.2s;
        }

        .image-item:hover {
            transform: translateY(-2px);
        }

        .image-item.opacity-50 {
            opacity: 0.5;
        }

        .image-item.scale-95 {
            transform: scale(0.95);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush
