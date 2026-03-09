<div class="space-y-6">

    <div class="bg-white p-6 rounded-lg shadow">

        <h2 class="text-lg font-semibold mb-4">
            {{ $seasonId ? 'Editar Temporada' : 'Nova Temporada' }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <input type="text"
                   placeholder="Nome da temporada"
                   wire:model="label"
                   class="border rounded p-2">

            <input type="date"
                   wire:model="start_date"
                   class="border rounded p-2">

            <input type="date"
                   wire:model="end_date"
                   class="border rounded p-2">

            <input type="number"
                   step="0.01"
                   placeholder="Preço por dia"
                   wire:model="price_per_day"
                   class="border rounded p-2">

        </div>

        <div class="mt-4 flex gap-2">

            <button wire:click="save"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                Salvar
            </button>

            <button wire:click="resetForm"
                    class="bg-gray-300 px-4 py-2 rounded">
                Cancelar
            </button>

        </div>

    </div>

    <div class="bg-white rounded shadow">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Temporada</th>
                    <th class="p-3">Início</th>
                    <th class="p-3">Fim</th>
                    <th class="p-3">Preço</th>
                    <th class="p-3"></th>
                </tr>
            </thead>

            <tbody>

                @forelse($seasons as $season)

                    <tr class="border-t">

                        <td class="p-3">{{ $season->label }}</td>

                        <td class="p-3 text-center">
                            {{ $season->start_date->format('d/m/Y') }}
                        </td>

                        <td class="p-3 text-center">
                            {{ $season->end_date->format('d/m/Y') }}
                        </td>

                        <td class="p-3 text-center">
                            R$ {{ number_format($season->price_per_day,2,',','.') }}
                        </td>

                        <td class="p-3 flex gap-2 justify-end">

                            <button wire:click="edit({{ $season->id }})"
                                    class="text-blue-600">
                                Editar
                            </button>

                            <button wire:click="delete({{ $season->id }})"
                                    class="text-red-600">
                                Excluir
                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">
                            Nenhuma temporada cadastrada
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
