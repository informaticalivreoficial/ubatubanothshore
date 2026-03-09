<div
    x-data="calendarComponent(@js($events))"
    x-init="initCalendar()"
    wire:ignore
>
    <div id="calendar"></div>
</div>

<script>
function calendarComponent(events) {

    return {

        calendar: null,        

        initCalendar() {

            this.calendar = new Calendar(document.getElementById('calendar'), {

                plugins: [ dayGridPlugin, interactionPlugin ],
                initialView: 'dayGridMonth',
                locale: ptBrLocale,
                height: 'auto',
                selectable: true,
                selectMirror: true,
                events: events, 

                select: async (info) => {

                    const hasReservation = this.calendar.getEvents().some(event => {
                        if (event.extendedProps.type !== 'reservation') return false
                        return (info.start < event.end && info.end > event.start)
                    })

                    if (hasReservation) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Ops!',
                            text: 'Não é possível bloquear datas com reserva.',
                            confirmButtonColor: '#ef4444'
                        })
                        this.calendar.unselect()
                        return
                    }

                    const result = await Swal.fire({
                        icon: 'question',
                        title: 'Bloquear datas?',
                        text: 'Deseja bloquear o período selecionado?',
                        showCancelButton: true,
                        confirmButtonText: 'Sim, bloquear',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#374151',
                        cancelButtonColor: '#6b7280'
                    })

                    if (result.isConfirmed) {
                        Livewire.dispatch('blockRange', {
                            start: info.startStr,
                            end: info.endStr
                        })
                    }

                    this.calendar.unselect()

                },

                eventClick: async (info) => {

                    if (info.event.extendedProps.type === 'season') return

                    if (info.event.extendedProps.type === 'reservation') {
                        window.open(info.event.extendedProps.url, '_blank')
                        return
                    }

                    if (info.event.extendedProps.type === 'blocked') {

                        const result = await Swal.fire({
                            icon: 'warning',
                            title: 'Remover bloqueio?',
                            text: 'Deseja desbloquear esta data?',
                            showCancelButton: true,
                            confirmButtonText: 'Sim, desbloquear',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#6b7280'
                        })

                        if (result.isConfirmed) {
                            Livewire.dispatch('unblockDate', {
                                date: info.event.startStr
                            })
                        }

                    }

                },

                eventDidMount: (info) => {
                    if (info.event.extendedProps.type === 'season') {
                        info.el.style.color = '#ffffff'
                        info.el.style.fontWeight = '600'
                    }
                }

            })
            

            this.calendar.render()

            // 🔄 atualizar calendário
            Livewire.on('refreshCalendar', (events) => {
                this.calendar.pauseRendering()
                this.calendar.removeAllEvents()
                events[0].forEach(event => {
                    this.calendar.addEvent(event)
                })
                this.calendar.resumeRendering()
            })            

        }

        

    }

}

</script>
