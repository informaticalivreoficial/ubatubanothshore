<div 
    wire:ignore
    x-data="reservationCalendar(
        @entangle('blockedDates'),
        @entangle('check_in'),
        @entangle('check_out')
    )"
    x-init="init()"
>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-user mr-2"></i> {{ $reservation ? 'Editar Reserva' : 'Nova Reserva' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reservations.index') }}">Reservas</a></li>
                        <li class="breadcrumb-item active">{{ $reservation ? 'Editar Reserva' : 'Nova Reserva' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-teal card-outline">            
        <div class="card-body"> 
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="labelforms"><b>Imóvel</b></label>
                        <select class="form-control" wire:model="property_id">
                            @foreach($properties as $id => $title)
                                <option value="{{ $id }}">
                                    {{ $title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="labelforms"><b>Hóspede responsável</b></label>
                        <input type="text" class="form-control @error('guest_name') is-invalid @enderror" id="guest_name" placeholder="Nome" wire:model="guest_name">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label class="labelforms"><b>Email</b></label>
                        <input type="text" class="form-control @error('guest_email') is-invalid @enderror" id="guest_email" placeholder="Email" wire:model="guest_email">
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label class="labelforms"><b>Telefone</b></label>
                        <input type="text" class="form-control @error('guest_phone') is-invalid @enderror" id="guest_phone" placeholder="Email" wire:model="guest_phone">
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label class="labelforms"><b>Status</b></label>
                        <select class="form-control" wire:model="status">
                            <option value="pending">Pendente</option>
                            <option value="confirmed">Confirmada</option>
                            <option value="cancelled">Cancelada</option>
                            <option value="finished">Finalizada</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="my-4 border-gray-300">

            <div 
                x-init="
                    flatpickr($refs.checkin,{
                        dateFormat:'Y-m-d',
                        altInput:true,
                        altFormat:'d/m/Y',
                        locale:'pt',
                        defaultDate: @js($check_in),
                        onChange: (selectedDates, dateStr) => {
                            $wire.set('check_in', dateStr)
                        }
                    });

                    flatpickr($refs.checkout,{
                        dateFormat:'Y-m-d',
                        altInput:true,
                        altFormat:'d/m/Y',
                        locale:'pt',
                        defaultDate: @js($check_out),
                        onChange: (selectedDates, dateStr) => {
                            $wire.set('check_out', dateStr)
                        }
                    });
                "
            class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="labelforms"><b>Check-in</b></label>
                        <input
                            x-ref="checkin"
                            type="text"
                            class="form-control"
                            placeholder="Check-in"
                        >
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label class="labelforms"><b>Check-out</b></label>
                        <input
                            x-ref="checkout"
                            type="text"
                            class="form-control"
                            placeholder="Check-out"
                        >
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-2 col-lg-2">
                    <div class="form-group">
                        <label class="labelforms"><b>Hóspedes</b></label>
                        <input 
                            type="text"
                            class="form-control @error('guests') is-invalid @enderror"
                            wire:model.live="guests"
                            min="1"
                            max="{{ $maxGuests }}"
                        >
                        @error('guests')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-12 col-md-2 col-lg-2">
                    <div class="form-group">
                        <label class="labelforms"><b>diárias</b></label>
                        <input type="text" class="form-control" id="nights" wire:model="nights">
                    </div>
                </div>
                <div class="col-12 col-md-2 col-lg-2">
                    <div class="form-group">
                        <label class="labelforms"><b>Valor Diária</b></label>
                        <input type="text" class="form-control" id="daily_total" wire:model.live="daily_total">
                    </div>
                </div>
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="form-group">
                        <label class="labelforms"><b>Taxa limpeza</b></label>
                        <input type="text" class="form-control" id="cleaning_fee" wire:model.live="cleaning_fee">
                    </div>
                </div>
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="form-group">
                        <label class="labelforms"><b>Valor Total</b></label>
                        <input type="text" class="form-control" id="total_value" wire:model="total_value">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <label class="labelforms"><b>Observações</b></label>
                    <textarea rows="5" wire:model="notes" class="form-control"></textarea>
                </div>
            </div>

            <div class="row text-right">
                <div class="col-12 pb-4 mt-3">
                    <button type="button" wire:click="save" class="btn btn-lg btn-success p-3">
                        <i class="nav-icon fas fa-check mr-2"></i>
                        {{ $reservation ? 'Atualizar Agora' : 'Cadastrar Agora' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        window.reservationCalendar = (blockedDates, checkIn, checkOut) => ({

    blockedDates,
    checkIn,
    checkOut,

    checkin: null,
    checkout: null,

    init(){

        this.checkin = flatpickr(this.$refs.checkin,{
            dateFormat:'Y-m-d',
            altInput:true,
            altFormat:'d/m/Y',
            locale:'pt',
            disable: this.blockedDates,
            defaultDate: this.checkIn || null,

            onChange: (selectedDates, dateStr) => {
                this.checkIn = dateStr
                $wire.set('check_in', dateStr)
            }
        });

        this.checkout = flatpickr(this.$refs.checkout,{
            dateFormat:'Y-m-d',
            altInput:true,
            altFormat:'d/m/Y',
            locale:'pt',
            disable: this.blockedDates,
            defaultDate: this.checkOut || null,

            onChange: (selectedDates, dateStr) => {
                this.checkOut = dateStr
                $wire.set('check_out', dateStr)
            }
        });

        // 🔥 sincroniza quando Livewire atualizar
        this.$watch('checkIn', value => {
            if(value){
                this.checkin.setDate(value, false)
            }
        });

        this.$watch('checkOut', value => {
            if(value){
                this.checkout.setDate(value, false)
            }
        });

    }

});
    </script>
@endpush