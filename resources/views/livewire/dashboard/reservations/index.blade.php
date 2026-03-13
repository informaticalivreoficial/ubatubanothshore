<div>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-ticket-alt mr-2"></i> Reservas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">                    
                        <li class="breadcrumb-item"><a href="{{route('admin')}}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Reservas</li>
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
                        <div class="flex flex-col md:flex-row gap-4 mb-6">
                            <input
                                type="text"
                                wire:model.live="search"
                                placeholder="Buscar por nome ou email..."
                                class="border rounded-lg px-3 py-2 w-full md:w-64"
                            >

                            <select
                                wire:model.live="status"
                                class="border rounded-lg px-3 py-2 w-full md:w-48"
                            >
                                <option value="">Todos status</option>
                                <option value="pending">Pendente</option>
                                <option value="waiting_payment">Aguardando pagamento</option>
                                <option value="paid">Pago</option>
                                <option value="confirmed">Confirmada</option>
                                <option value="cancelled">Cancelada</option>
                                <option value="expired">Expirada</option>
                                <option value="finished">Finalizada</option>
                            </select>

                        </div>
                      </div>
                </div>
                <div class="col-12 col-sm-6 my-2 text-right">
                    <a href="{{ route('reservations.create') }}" class="btn btn-sm btn-default"><i class="fas fa-plus mr-2"></i> Cadastrar Novo</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped projects">
                <thead>
                    <tr>
                        <th class="p-3">Imóvel</th>
                        <th class="p-3">Período</th>
                        <th class="p-3">Total</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)                    
                        <tr>                            
                            <td class="p-3">{{ $reservation->property->title ?? '-' }}</td>
                            <td class="p-3">
                                {{ $reservation->check_in->format('d/m/Y') }}
                                -
                                {{ $reservation->check_out->format('d/m/Y') }}
                            </td>
                            <td class="p-3 font-semibold">
                                R$ {{ number_format($reservation->total_value, 2, ',', '.') }}
                            </td>
                            <td class="p-3">
                                @php
                                    $colors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'waiting_payment' => 'bg-orange-100 text-orange-800',
                                        'paid' => 'bg-emerald-100 text-emerald-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                        'expired' => 'bg-gray-100 text-gray-800',
                                        'finished' => 'bg-blue-100 text-blue-800',
                                    ];
                                @endphp

                                <span class="px-2 py-1 text-xs rounded-full {{ $colors[$reservation->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                @if($reservation->user->whatsapp)
                                    <a target="_blank" 
                                        href="" 
                                        class="btn btn-xs bg-teal"><i class="fab fa-whatsapp"></i>
                                    </a>
                                @endif
                                @if($reservation->user->email)
                                    <a target="_blank" 
                                        href="mailto:{{ $reservation->user->email }}" 
                                        class="btn btn-xs bg-primary"><i class="fas fa-envelope"></i>
                                    </a>
                                @endif
                                <button
                                    wire:click="showReservation({{ $reservation->id }})"
                                    class="btn btn-xs btn-info"
                                >
                                    <i class="fas fa-search"></i>
                                </button>
                                <a href="{{ route('reservations.edit', [ 'reservation' => $reservation->id ]) }}" 
                                    class="btn btn-xs btn-default" 
                                    title="Editar">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <select
                                    wire:change="updateStatus({{ $reservation->id }}, $event.target.value)"
                                    class="border rounded px-2 py-1 text-xs"
                                >
                                    <option value="pending" @selected($reservation->status === 'pending')>
                                        Pendente
                                    </option>

                                    <option value="waiting_payment" @selected($reservation->status === 'waiting_payment')>
                                        Aguardando pagamento
                                    </option>

                                    <option value="paid" @selected($reservation->status === 'paid')>
                                        Pago
                                    </option>

                                    <option value="confirmed" @selected($reservation->status === 'confirmed')>
                                        Confirmada
                                    </option>

                                    <option value="cancelled" @selected($reservation->status === 'cancelled')>
                                        Cancelada
                                    </option>

                                    <option value="expired" @selected($reservation->status === 'expired')>
                                        Expirada
                                    </option>

                                    <option value="finished" @selected($reservation->status === 'finished')>
                                        Finalizada
                                    </option>
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-500">
                                Nenhuma reserva encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>                
            </table>
            <div class="card-footer clearfix">  
                {{ $reservations->links() }} 
            </div>
        </div>
    </div>


    @if($showModal && $selectedReservation)
        <div class="fixed inset-0 z-[1055] flex items-center justify-center bg-black/50">

            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold">
                        Detalhes da Reserva
                    </h2>

                    <button wire:click="closeModal" class="text-gray-500">
                        ✕
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <h3 class="font-semibold text-sm text-gray-500">Hóspede</h3>
                        <p>{{ $selectedReservation->guest_name }}</p>
                        <p class="text-sm text-gray-500">{{ $selectedReservation->guest_email }}</p>
                        <p class="text-sm">{{ $selectedReservation->guest_phone }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-sm text-gray-500">Imóvel</h3>

                        <p>{{ $selectedReservation->property->title ?? '-' }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-sm text-gray-500">Check-in</h3>

                        <p>{{ $selectedReservation->check_in->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-sm text-gray-500">Check-out</h3>

                        <p>{{ $selectedReservation->check_out->format('d/m/Y') }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-sm text-gray-500">Hóspedes</h3>

                        <p>{{ $selectedReservation->guests }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-sm text-gray-500">Noites</h3>

                        <p>{{ $selectedReservation->nights }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-sm text-gray-500">Taxa limpeza</h3>

                        <p>R$ {{ number_format($selectedReservation->cleaning_fee,2,',','.') }}</p>
                    </div>

                    <div>
                        <h3 class="font-semibold text-sm text-gray-500">Total</h3>

                        <p class="font-semibold">
                            R$ {{ number_format($selectedReservation->total_value,2,',','.') }}
                        </p>
                    </div>

                </div>

                @if($selectedReservation->notes)
                <div class="mt-4">
                    <h3 class="font-semibold text-sm text-gray-500">Observações</h3>
                    <p class="text-sm">{{ $selectedReservation->notes }}</p>
                </div>
                @endif

                <div class="mt-6 flex justify-between">

                    @if($selectedReservation->status === 'pending')

                        <button
                            wire:click="sendFormLink({{ $selectedReservation->id }})"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"
                        >
                            Enviar Formulário da Reserva
                        </button>                        

                    @endif

                    <button
                        wire:click="closeModal"
                        class="bg-gray-200 px-4 py-2 rounded-lg"
                    >
                        Fechar
                    </button>

                </div>

            </div>

        </div>

        @endif
</div>
