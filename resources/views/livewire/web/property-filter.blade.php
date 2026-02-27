<div>
    <form wire:submit.prevent>
        <div class="search-area">
            <div class="container">
                <div class="search-area-inner">
                    <div class="search-contents ">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label>Alugar ou Comprar?</label>
                                    <select class="search-fields" wire:model.live="operation">
                                        <option value="">Selecione</option>
                                        <option value="location">Alugar</option>
                                        <option value="sale">Comprar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Escolha a Cidade</label>
                                    <select class="search-fields" wire:model.live="cidade">
                                        <option value="">Selecione</option>
                                        @foreach($cidades as $cidade)
                                            <option value="{{ $cidade }}">{{ $cidade }}</option>
                                        @endforeach                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Bairro</label>
                                    <select wire:model.live="bairro" class="search-fields">
                                        <option value="">Selecione</option>
                                        @foreach($bairros as $bairro)
                                            <option value="{{ $bairro }}">{{ $bairro }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Valores</label>                                        
                                    <select class="search-fields" wire:model.live="valores" @disabled(empty($valoresOptions))>
                                        <option value="">Todos</option>
                                        @foreach($valoresOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>                                        
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label>Quartos</label>
                                    <select class="search-fields" wire:model.live="dormitorios">
                                        <option value="">Todos</option>
                                        <option value="1">1+</option>
                                        <option value="2">2+</option>
                                        <option value="3">3+</option>
                                        <option value="4">4+</option>
                                        <option value="5">5+</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
