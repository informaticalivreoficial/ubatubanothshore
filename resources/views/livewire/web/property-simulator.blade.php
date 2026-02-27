<div>
    <div 
        x-data="{ 
                    tipo: @entangle('tipo_financiamento'),
                    success: @entangle('success')
                }" 
        class="max-w-6xl mx-auto px-6 py-10"
    >
        <h1 class="text-2xl font-semibold text-teal-600 mb-6 text-center">Simulador de Financiamento</h1>

        <form wire:submit.prevent="submit" class="space-y-6 bg-white p-6 rounded-xl shadow-md" x-show="!success" x-transition.opacity.duration.500ms>
            {{-- Dados do Cliente --}}
            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Nome</label>
                    <input type="text" wire:model="nome"
                        class="w-full h-12 border border-gray-200 rounded-lg mt-1 px-3 focus:ring-2 focus:ring-teal-400 focus:border-teal-500 transition-all duration-150" />
                    @error('nome') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">Telefone</label>
                    <input type="text" wire:model="telefone" x-mask="(99) 99999-9999"
                        class="w-full h-12 border border-gray-200 rounded-lg mt-1 px-3 focus:ring-2 focus:ring-teal-400 focus:border-teal-500 transition-all duration-150" />
                    @error('telefone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">E-mail</label>
                    <input type="email" wire:model="email"
                        class="w-full h-12 border border-gray-200 rounded-lg mt-1 px-3 focus:ring-2 focus:ring-teal-400 focus:border-teal-500 transition-all duration-150" />
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Estado</label>
                    <select wire:model="estado"
                        class="w-full h-12 border border-gray-200 rounded-lg mt-1 px-3 bg-white focus:ring-2 focus:ring-teal-400 focus:border-teal-500 transition-all duration-150">
                        <option value="">Selecione</option>
                        @foreach ($estados as $uf)
                            <option value="{{ $uf['sigla'] }}">{{ $uf['nome'] }}</option>
                        @endforeach
                    </select>
                    @error('estado') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">Cidade</label>
                    <input type="text" wire:model="cidade"
                    class="w-full h-12 border border-gray-200 rounded-lg mt-1 px-3 focus:ring-2 focus:ring-teal-400 focus:border-teal-500 transition-all duration-150"
                    placeholder="Digite sua cidade">
                    @error('cidade') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-600">Quando pretende se mudar?</label>
                    <select wire:model="tempo"
                        class="w-full h-12 border border-gray-200 rounded-lg mt-1 px-3 bg-white focus:ring-2 focus:ring-teal-400 focus:border-teal-500 transition-all duration-150">
                        <option value="">Selecione</option>
                        <option value="Quanto antes">Quanto antes</option>
                        <option value="Até 3 meses">Até 3 meses</option>
                        <option value="Entre 3 e 6 meses">Entre 3 e 6 meses</option>
                        <option value="Entre 6 meses e 1 ano">Entre 6 meses e 1 ano</option>
                        <option value="Mais de 1 ano">Mais de 1 ano</option>
                    </select>
                    @error('tempo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">Escolha um tipo de Financiamento</label>
                    <select 
                    x-model="tipo" 
                    wire:model="tipo_financiamento"
                    x-on:change="
                            if ($el.value !== 'Consórcio') {
                                $wire.set('valorcarta', '');
                                $wire.set('prazocarta', '');
                            }
                        "
                        class="w-full h-12 border border-gray-200 rounded-lg mt-1 px-3 bg-white focus:ring-2 focus:ring-teal-400 focus:border-teal-500 transition-all duration-150">
                        <option value="">Selecione</option>
                        <option value="Financiamento">Financiamento Imobiliário</option>
                        <option value="Consórcio">Consórcio Imobiliário</option>
                    </select>
                    @error('tipo_financiamento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Dados do Consórcio --}}
            <div class="grid md:grid-cols-2 gap-4" x-show="tipo === 'Consórcio'" x-transition>
                <div 
                    x-data="{
                        valorFormatado: '',
                        formatar(valor) {
                            let num = valor.replace(/[^\d]/g, '');
                            if (num === '') {
                                this.valorFormatado = '';
                                $wire.valorcarta = null;
                                return;
                            }
                            this.valorFormatado = (num / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                            $wire.valorcarta = num / 100;
                        }
                    }"
                    x-init="
                        if ($wire.valorcarta) {
                            valorFormatado = ($wire.valorcarta).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        }
                        // Observa mudanças no tipo de financiamento e limpa o campo se necessário
                        $watch('$wire.tipo_financiamento', value => {
                            if (value !== 'Consórcio') {
                                valorFormatado = '';
                                $wire.valorcarta = null;
                            }
                        });
                    "
                >
                    <label class="text-sm text-gray-600">Valor da Carta de Crédito</label>
                    <input 
                        type="text"
                        x-model="valorFormatado"
                        x-on:input="formatar($el.value)"
                        class="w-full h-12 border border-gray-200 rounded-lg mt-1 px-3 bg-white focus:ring-2 focus:ring-teal-400 focus:border-teal-500 transition-all duration-150"
                        placeholder="Ex: R$ 350.000,00"
                    >
                    @error('valorcarta') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label class="text-sm text-gray-600">Prazo</label>
                    <select wire:model="prazocarta"
                        class="w-full h-12 border border-gray-200 rounded-lg mt-1 px-3 bg-white focus:ring-2 focus:ring-teal-400 focus:border-teal-500 transition-all duration-150">
                        <option value="">Selecione</option>
                        <option value="36">36</option>
                        <option value="48">48</option>
                        <option value="60">60</option>
                        <option value="72">72</option>
                        <option value="120">120</option>
                        <option value="180">180</option>
                    </select>
                    @error('prazocarta') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Dados do Financiamento --}}
            <div class="grid md:grid-cols-3 gap-4 pb-2" x-show="tipo === 'Financiamento'" x-transition>
                <div 
                    x-data="{
                        valorFormatado: '',
                        formatar(valor) {
                            let num = valor.replace(/[^\d]/g, '');
                            if (num === '') {
                                this.valorFormatado = '';
                                $wire.valor = null;
                                return;
                            }
                            this.valorFormatado = (num / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                            $wire.valor = num / 100;
                        }
                    }"
                    x-init="
                        if ($wire.valor) {
                            valorFormatado = ($wire.valor).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        }
                        // Observa mudanças no tipo de financiamento e limpa o campo se necessário
                        $watch('$wire.tipo_financiamento', value => {
                            if (value !== 'Consórcio') {
                                valorFormatado = '';
                                $wire.valor = null;
                            }
                        });
                    "
                >
                    <label class="text-sm text-gray-600">Valor do imóvel</label>
                    <input 
                        type="text"
                        x-model="valorFormatado"
                        x-on:input="formatar($el.value)"
                        class="w-full h-12 border border-gray-200 rounded-lg mt-1 px-3 bg-white focus:ring-2 focus:ring-teal-400 focus:border-teal-500 transition-all duration-150"
                        placeholder="Ex: R$ 350.000,00"
                    >
                    @error('valor') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <div 
                    x-data="{
                        valorFormatado: '',
                        formatar(valor) {
                            let num = valor.replace(/[^\d]/g, '');
                            if (num === '') {
                                this.valorFormatado = '';
                                $wire.valor_entrada = null;
                                return;
                            }
                            this.valorFormatado = (num / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                            $wire.valor_entrada = num / 100;
                        }
                    }"
                    x-init="
                        if ($wire.valor_entrada) {
                            valorFormatado = ($wire.valor_entrada).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        }
                        // Observa mudanças no tipo de financiamento e limpa o campo se necessário
                        $watch('$wire.tipo_financiamento', value => {
                            if (value !== 'Consórcio') {
                                valorFormatado = '';
                                $wire.valor_entrada = null;
                            }
                        });
                    "
                >
                    <label class="text-sm text-gray-600">Valor de entrada</label>
                    <input 
                        type="text"
                        x-model="valorFormatado"
                        x-on:input="formatar($el.value)"
                        class="w-full h-12 border border-gray-200 rounded-lg mt-1 px-3 bg-white focus:ring-2 focus:ring-teal-400 focus:border-teal-500 transition-all duration-150"
                        placeholder="Ex: R$ 350.000,00"
                    >
                    @error('valor_entrada') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div> 
                
                <div 
                    x-data="{
                        valorFormatado: '',
                        formatar(valor) {
                            let num = valor.replace(/[^\d]/g, '');
                            if (num === '') {
                                this.valorFormatado = '';
                                $wire.renda = null;
                                return;
                            }
                            this.valorFormatado = (num / 100).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                            $wire.renda = num / 100;
                        }
                    }"
                    x-init="
                        if ($wire.renda) {
                            valorFormatado = ($wire.renda).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        }
                        // Observa mudanças no tipo de financiamento e limpa o campo se necessário
                        $watch('$wire.tipo_financiamento', value => {
                            if (value !== 'Consórcio') {
                                valorFormatado = '';
                                $wire.renda = null;
                            }
                        });
                    "
                >
                    <label class="text-sm text-gray-600">Renda Mensal</label>
                    <input 
                        type="text"
                        x-model="valorFormatado"
                        x-on:input="formatar($el.value)"
                        class="w-full h-12 border border-gray-200 rounded-lg mt-1 px-3 bg-white focus:ring-2 focus:ring-teal-400 focus:border-teal-500 transition-all duration-150"
                        placeholder="Ex: R$ 350.000,00"
                    >
                    @error('renda') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>                
            </div>

            <div class="grid md:grid-cols-2 gap-6 pt-6 border-t border-gray-200" x-show="tipo === 'Financiamento'" x-transition>
                <!-- Natureza do imóvel -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Natureza do Imóvel</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" wire:model="natureza" value="Indiferente" class="text-teal-600 focus:ring-teal-500">
                            <span class="text-gray-700">Indiferente</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" wire:model="natureza" value="Novo" class="text-teal-600 focus:ring-teal-500">
                            <span class="text-gray-700">Novo</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" wire:model="natureza" value="Usado" class="text-teal-600 focus:ring-teal-500">
                            <span class="text-gray-700">Usado</span>
                        </label>
                    </div>
                </div>

                <!-- Tipo do imóvel -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Tipo do Imóvel</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" wire:model="tipoimovel" value="Residencial" class="text-teal-600 focus:ring-teal-500">
                            <span class="text-gray-700">Residencial</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" wire:model="tipoimovel" value="Comercial" class="text-teal-600 focus:ring-teal-500">
                            <span class="text-gray-700">Comercial</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" wire:model="tipoimovel" value="Rural" class="text-teal-600 focus:ring-teal-500">
                            <span class="text-gray-700">Rural</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="space-y-4 pt-6 border-t border-gray-200" x-show="tipo === 'Financiamento'" x-transition>
                <h4 class="text-lg font-semibold text-gray-800">Gostaria de pré-aprovar o seu crédito?</h4>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">O que você precisa agora?</label>
                    <div class="flex flex-wrap gap-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="oqueprecisa" wire:model="oqueprecisa" value="Pré aprovar meu crédito" class="text-teal-600 focus:ring-teal-500">
                            <span class="text-gray-700">Pré aprovar meu crédito</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="oqueprecisa" wire:model="oqueprecisa" value="Refinanciamento Imobiliário" class="text-teal-600 focus:ring-teal-500">
                            <span class="text-gray-700">Refinanciamento Imobiliário</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="radio" name="oqueprecisa" wire:model="oqueprecisa" value="Receber opções de Imóveis" class="text-teal-600 focus:ring-teal-500">
                            <span class="text-gray-700">Receber opções de Imóveis</span>
                        </label>
                    </div>
                </div>
            </div>
           

            {{-- Botão --}}
            <div class="text-center pt-4">
                <button 
                    type="submit" 
                    class="px-8 py-3 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700 transition-colors"
                    wire:loading.attr="disabled"
                >
                <span wire:loading.remove>Enviar Simulação</span>
                <span wire:loading class="flex items-center">Enviando...</span>
                </button>               
            </div>
        </form>

        <div x-show="success" x-transition.opacity.duration.500ms class="bg-green-100 text-green-800 p-3 rounded-lg mb-6 text-center">
            ✅ Sua simulação foi enviada a um especialista de plantão com sucesso! Em breve entraremos em contato.
        </div>
    </div>
</div>
