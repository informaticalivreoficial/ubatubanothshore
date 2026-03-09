<div wire:poll.30s="refreshNotifications">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-bell mr-2"></i> Notificações</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Notificações</li>
                    </ol>
                </div>
            </div>
        </div>    
    </div>

    <div class="card">
        <div class="card-header">
            <div class="row">                
                <div class="col-12 my-2 text-right">
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <button
                            wire:click="markAllAsRead"
                            class="btn btn-sm btn-outline-success"
                            title="Marcar todas como lidas"
                        >
                            <i class="fas fa-check-double"></i>                            
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            @forelse($notifications as $notification)
                <div
                    class="group d-flex align-items-start p-3 border-bottom
                    transition-all duration-200 ease-in-out
                    hover:bg-slate-50 hover:shadow-sm
                    {{ is_null($notification->read_at) ? 'bg-slate-100' : 'bg-white' }}"
                >
                    {{-- Ícone --}}
                    <div class="mr-3 mt-1">
                        <span class="
                            inline-flex items-center justify-center
                            rounded-full bg-yellow-100 text-yellow-600
                            p-2
                            transition-transform duration-200
                            group-hover:scale-110
                        ">
                            <i class="fas fa-bell"></i>
                        </span>
                    </div>

                    {{-- Conteúdo --}}
                    <div class="flex-grow-1">
                        <p class="mb-1 font-weight-bold">
                            Reserva: {{ $notification->data['reservation_id'] }}
                        </p>

                        @php
                            $checkin = $notification->data['check_in'] ?? null;
                            $checkout = $notification->data['check_out'] ?? null;
                        @endphp

                        <small class="text-muted d-block">
                            Cliente: 
                            <strong>
                                {{ $notification->data['guest_name'] ?? 'Sistema' }}
                                - Checkin: {{ $checkin ? \Carbon\Carbon::parse($checkin)->format('d/m/Y') : '' }}
                                - Checkout: {{ $checkout ? \Carbon\Carbon::parse($checkout)->format('d/m/Y') : '' }}
                            </strong>
                        </small>

                        <small class="text-muted">
                            <i class="far fa-clock mr-1"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </small>
                    </div>

                    {{-- Ações --}}
                    <div class="ml-3 text-right">
                        @if(isset($notification->data['url']))
                            <a
                                href="{{ $notification->data['url'] }}"
                                target="_blank"
                                wire:click="markAsRead('{{ $notification->id }}')"
                                class="btn btn-sm btn-outline-primary"
                                title="Visualizar"
                            >
                                <i class="fas fa-search "></i>
                            </a>
                        @endif

                        @if(is_null($notification->read_at))
                            <button
                                wire:click="markAsRead('{{ $notification->id }}')"
                                class="btn btn-sm btn-outline-success"
                                title="Marcar como Lida"
                            >
                                <i class="fas fa-check "></i>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="far fa-bell-slash fa-3x mb-3"></i>
                    <p>Nenhuma notificação encontrada</p>
                </div>
            @endforelse
        </div>

        {{-- Paginação --}}
        @if($notifications->hasPages())
            <div class="card-footer">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>    
</div>
