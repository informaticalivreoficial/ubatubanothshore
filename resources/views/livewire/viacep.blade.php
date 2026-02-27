<div>
    <label for="cep">CEP:</label>
    <input 
        type="text" 
        id="cep" 
        wire:model.debounce.500ms="cep" 
        placeholder="Digite o CEP"
        maxlength="8"
        oninput="maskCep(this)"
    >
    
    @if ($erro)
        <div style="color: red;">{{ $erro }}</div>
    @endif

    @if (!$erro && $cep && strlen($cep) == 8)
        <div>
            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" value="{{ $endereco }}" disabled>

            <label for="bairro">Bairro:</label>
            <input type="text" id="bairro" value="{{ $bairro }}" disabled>

            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" value="{{ $cidade }}" disabled>

            <label for="estado">Estado:</label>
            <input type="text" id="estado" value="{{ $estado }}" disabled>
        </div>
    @endif
</div>

<script>
    // Máscara para o campo CEP
    function maskCep(input) {
        input.value = input.value.replace(/\D/g, '').replace(/(\d{5})(\d{3})/, '$1-$2');
    }
</script>
