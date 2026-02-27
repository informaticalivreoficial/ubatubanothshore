<div>
    <div x-data="{ success: @entangle('success') }">
        <form wire:submit.prevent="submit" x-show="!success" x-transition.opacity.duration.500ms>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix">
                    {{--HONEYPOT--}}
                    <input type="hidden" wire:model="bairro" />
                    <input type="text" class="hidden" wire:model="cidade" />
                </div>
            </div>        
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group fullname">
                        <input type="text" wire:model="nome" class="input-text @error('nome') is-invalid @enderror" placeholder="Seu Nome"/>
                        @error('nome') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group enter-email">
                        <input type="email" wire:model="email" class="input-text @error('email') is-invalid @enderror" placeholder="Seu E-mail"/>
                        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>  
                <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <button 
                            style="width: 100%;" 
                            type="submit" 
                            class="button-sm button-theme"
                            wire:loading.attr="disabled"
                        >
                        <span wire:loading.remove>Enviar Mensagem</span>
                        <span wire:loading class="flex items-center">Enviando...</span>
                        </button>
                    </div>
                </div>
            </div>        
        </form>

        <div x-show="success" x-transition.opacity.duration.500ms class="alert alert-success">
            ✅ Consulta enviada com sucesso! Entraremos em contato em breve.
        </div>
    </div>
</div>