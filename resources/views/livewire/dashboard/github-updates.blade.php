<div class="col-lg-4">    
    <div class="card">
        <div class="card-header border-transparent">
            <h3 class="card-title">Últimas Atualizações do Sistema</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="card-body" style="overflow-y: auto;">
            @foreach($commits as $commit)
                <div class="mb-3 border-bottom pb-2">
                    <strong>{{ $commit['commit']['message'] }}</strong>
                    <br>
                    <small>
                        Autor: {{ $commit['commit']['author']['email'] }} —
                        {{ \Carbon\Carbon::parse($commit['commit']['author']['date'])->diffForHumans() }}
                    </small>
                </div>
            @endforeach
        </div>
        <div class="card-footer clearfix">
            <button wire:click="loadCommits" 
                    wire:loading.attr="disabled"
                    wire:target="loadCommits"
                    class="btn btn-sm btn-secondary float-right">

                <span wire:loading.remove wire:target="loadCommits">
                    Atualizar
                </span>

                <span wire:loading wire:target="loadCommits">
                    <i class="fas fa-spinner fa-spin"></i> Carregando...
                </span>
            </button>
        </div>
    </div>   
</div>
