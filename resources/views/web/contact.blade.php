@extends("web.layouts.app")

@section('content') 
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-3 gap-12">
                
                <div class="lg:col-span-2 order-1 lg:order-1">                
                    <livewire:web.contact-form  /> 
                </div>
                
                <aside class="lg:col-span-1 order-2 lg:order-2">
                    <div class="space-y-8">

                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 border-b pb-2">
                                Outros Canais
                            </h3>
                        </div>

                        {{-- ENDEREÇO --}}
                        @if ($configuracoes->display_address)
                            <div class="flex items-start gap-4">
                                <div class="text-blue-600 text-xl mt-1">
                                    <i class="fa fa-map-marker"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Escritório</h4>
                                    <p class="text-gray-600 leading-relaxed">
                                        @if ($configuracoes->street) {{ $configuracoes->street }} @endif
                                        @if ($configuracoes->number) , {{ $configuracoes->number }} @endif
                                        @if ($configuracoes->neighborhood) , {{ $configuracoes->neighborhood }} @endif
                                        @if ($configuracoes->city) - {{ $configuracoes->city }} @endif
                                        @if ($configuracoes->state) / {{ $configuracoes->state }} @endif
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- TELEFONE --}}
                        @if ($configuracoes->phone || $configuracoes->cell_phone)
                            <div class="flex items-start gap-4">
                                <div class="text-blue-600 text-xl mt-1">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">Telefone</h4>

                                    @if($configuracoes->phone)
                                        <p>
                                            <a href="tel:{{$configuracoes->phone}}" 
                                            class="text-gray-600 hover:text-blue-600 transition">
                                                {{$configuracoes->phone}}
                                            </a>
                                        </p>
                                    @endif

                                    @if($configuracoes->cell_phone)
                                        <p>
                                            <a href="tel:{{$configuracoes->cell_phone}}" 
                                            class="text-gray-600 hover:text-blue-600 transition">
                                                {{$configuracoes->cell_phone}}
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- WHATSAPP --}}
                        @if ($configuracoes->whatsapp)
                            <div class="flex items-start gap-4">
                                <div class="text-green-600 text-xl mt-1">
                                    <i class="fa fa-whatsapp"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">WhatsApp</h4>
                                    <p>
                                        <a target="_blank"
                                        onclick="shareWhatsApp(event)"
                                        href="#"
                                        class="text-gray-600 hover:text-green-600 transition">
                                            {{ $configuracoes->whatsapp }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        @endif

                        {{-- EMAIL --}}
                        @if ($configuracoes->email)
                            <div class="flex items-start gap-4">
                                <div class="text-blue-600 text-xl mt-1">
                                    <i class="fa fa-envelope"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">E-mail</h4>

                                    <p>
                                        <a href="mailto:{{$configuracoes->email}}" 
                                        class="text-gray-600 hover:text-blue-600 transition">
                                            {{$configuracoes->email}}
                                        </a>
                                    </p>

                                    @if ($configuracoes->additional_email)
                                        <p>
                                            <a href="mailto:{{$configuracoes->additional_email}}" 
                                            class="text-gray-600 hover:text-blue-600 transition">
                                                {{$configuracoes->additional_email}}
                                            </a>
                                        </p>
                                    @endif 
                                </div>
                            </div>
                        @endif

                    </div>
                </aside>

            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>        
        function shareWhatsApp(event) {
            event.preventDefault();

            const message = "Atendimento {{ $configuracoes->app_name }}";

            const phone = "{{ $configuracoes->whatsapp_number }}";

            const isMobile = /Android|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/i.test(navigator.userAgent);

            const whatsappUrl = isMobile
                ? `https://api.whatsapp.com/send?phone=${phone}&text=${message}`
                : `https://web.whatsapp.com/send?phone=${phone}&text=${message}`;

            window.open(whatsappUrl, '_blank');
        }
    </script>
@endpush