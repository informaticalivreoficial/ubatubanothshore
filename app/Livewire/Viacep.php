<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Viacep extends Component
{
    public $cep;             // CEP digitado
    public $endereco;        // Endereço resultante da consulta
    public $bairro;          // Bairro resultante da consulta
    public $cidade;          // Cidade resultante da consulta
    public $estado;          // Estado resultante da consulta
    public $erro;            // Mensagem de erro


    // Método para consultar o CEP
    public function updatedCep($value)
    {
        if (strlen($value) == 8) {  // Verifica se o CEP tem 8 dígitos
            $this->consultarCep($value);
        }
    }

    // Função para fazer a consulta via API
    public function consultarCep($cep)
    {
        $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

        if ($response->successful()) {
            $dados = $response->json();

            // Verifica se o CEP retornou um erro (ex: CEP não encontrado)
            if (isset($dados['erro']) && $dados['erro'] === true) {
                $this->erro = 'CEP não encontrado.';
                $this->resetEndereco();
            } else {
                $this->erro = null;
                $this->endereco = $dados['logradouro'] ?? '';
                $this->bairro = $dados['bairro'] ?? '';
                $this->cidade = $dados['localidade'] ?? '';
                $this->estado = $dados['uf'] ?? '';
            }
        } else {
            $this->erro = 'Erro ao consultar o CEP.';
            $this->resetEndereco();
        }
    }

    // Resetar os campos de endereço em caso de erro
    public function resetEndereco()
    {
        $this->endereco = '';
        $this->bairro = '';
        $this->cidade = '';
        $this->estado = '';
    }

    public function render()
    {
        return view('livewire.viacep');
    }
}
