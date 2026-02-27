<div>
    <input type="text" class="form-control @error('dataSelecionada') is-invalid @enderror" wire:model="dataSelecionada" id="datepicker" />
    @error('dataSelecionada')
        <span class="error erro-feedback">{{ $message }}</span>
    @enderror
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let input = document.getElementById('datepicker');
        flatpickr(input, {
            dateFormat: "d/m/Y",
            allowInput: true,
            maxDate: "today",
            onChange: function(selectedDates, dateStr, instance) {
                @this.set('dataSelecionada', dateStr);
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
    });
</script>
